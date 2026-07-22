import { useEffect, useState } from 'react';
import { Head, Link, usePage } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import { Tooltip, TooltipContent, TooltipTrigger } from '@/components/ui/tooltip';
import {
    CheckCircle2,
    ChevronLeft,
    ChevronRight,
    Clock,
    FileCheck,
    FileText,
    Link as LinkIcon,
    Lock,
    Menu,
    PanelLeftClose,
    PanelLeftOpen,
    PlayCircle,
} from 'lucide-react';
import {
    contentTypeOf,
    isAssessment,
    isLesson,
    type LearningCourse,
    type LearningEnrollment,
    type LearningModuleItem,
    type LearningPayload,
} from '@/types/learning';

interface PageProps {
    learning: LearningPayload;
    [key: string]: unknown;
}

const SIDEBAR_COLLAPSED_KEY = 'learning-sidebar-collapsed';

function formatDuration(seconds: number | null): string {
    if (!seconds) return '';
    const m = Math.round(seconds / 60);
    return `${m} min`;
}

/** Converts a plain YouTube watch URL into an embeddable one; passes anything else through untouched. */
function toEmbedUrl(url: string): string {
    const match = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w-]+)/);
    return match ? `https://www.youtube.com/embed/${match[1]}` : url;
}

function ItemIcon({ item, className }: { item: LearningModuleItem; className?: string }) {
    if (isAssessment(item)) return <FileCheck className={className} />;
    if (isLesson(item) && item.itemable.type === 'article') return <FileText className={className} />;
    if (isLesson(item) && (item.itemable.type === 'pdf' || item.itemable.type === 'link')) {
        return <LinkIcon className={className} />;
    }
    return <PlayCircle className={className} />;
}

function LessonViewer({ item }: { item: LearningModuleItem }) {
    if (!isLesson(item)) return null;
    const lesson = item.itemable;

    if (lesson.type === 'video' && lesson.content_url) {
        return (
            <div className="aspect-video w-full overflow-hidden rounded-lg bg-black">
                <iframe
                    src={toEmbedUrl(lesson.content_url)}
                    title={lesson.title}
                    className="h-full w-full"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowFullScreen
                />
            </div>
        );
    }

    if (lesson.type === 'article') {
        return (
            <div className="prose prose-neutral max-w-none">
                <p className="whitespace-pre-line text-foreground/90">{lesson.content_text}</p>
            </div>
        );
    }

    // pdf | link
    return (
        <div className="flex flex-col items-center gap-4 rounded-lg border border-dashed py-16 text-center">
            <LinkIcon className="h-8 w-8 text-muted-foreground" />
            <p className="text-muted-foreground">This lesson links to an external resource.</p>
            {lesson.content_url && (
                <Button asChild>
                    <a href={lesson.content_url} target="_blank" rel="noopener noreferrer">
                        Open resource
                    </a>
                </Button>
            )}
        </div>
    );
}

function AssessmentViewer({ item }: { item: LearningModuleItem }) {
    if (!isAssessment(item)) return null;
    const assessment = item.itemable;

    return (
        <div className="space-y-6 rounded-lg border bg-card p-6">
            <div>
                <Badge variant="secondary" className="mb-2 font-normal capitalize">
                    {assessment.type}
                </Badge>
                <h2 className="text-xl font-semibold text-foreground">{assessment.title}</h2>
                {assessment.description && (
                    <p className="mt-2 text-muted-foreground">{assessment.description}</p>
                )}
            </div>

            <div className="flex flex-wrap gap-6 text-sm text-muted-foreground">
                <span>Passing score: {assessment.passing_score}%</span>
                {assessment.duration_seconds && (
                    <span>Time limit: {formatDuration(assessment.duration_seconds)}</span>
                )}
                {assessment.max_attempts && <span>Max attempts: {assessment.max_attempts}</span>}
            </div>

            {/* AttemptController@store isn't built yet — this posts to a route
                that doesn't exist until that controller lands. */}
            <Button size="lg">Start assessment</Button>
        </div>
    );
}

/**
 * Full nav list (stats + progress + module/item tree). Used inside the
 * mobile Sheet as-is, and inside the desktop rail only when expanded —
 * see IconRail for the collapsed desktop state.
 */
function SidebarNav({
    course,
    enrollment,
    currentItem,
    statistics,
    onItemClick,
}: {
    course: LearningCourse;
    enrollment: LearningEnrollment;
    currentItem: LearningModuleItem;
    statistics: { totalModules: number; totalItems: number };
    onItemClick?: () => void;
}) {
    return (
        <>
            <div className="rounded-lg border bg-card p-4">
                <p className="text-xs text-muted-foreground">
                    {statistics.totalModules} module{statistics.totalModules === 1 ? '' : 's'} ·{' '}
                    {statistics.totalItems} item{statistics.totalItems === 1 ? '' : 's'}
                </p>
                <h1 className="mt-1 font-semibold text-foreground">{course.title}</h1>

                <div className="mt-3 h-2 w-full overflow-hidden rounded-full bg-muted">
                    <div
                        className="h-full rounded-full bg-primary transition-all"
                        style={{ width: `${enrollment.progress_percentage}%` }}
                    />
                </div>
                <p className="mt-1 text-xs text-muted-foreground">
                    {Math.round(enrollment.progress_percentage)}% complete
                </p>
            </div>

            <nav className="mt-4 space-y-4">
                {course.modules.map((module) => (
                    <div key={module.id}>
                        <p className="mb-2 px-1 text-xs font-medium uppercase tracking-wide text-muted-foreground">
                            {module.title}
                        </p>
                        <div className="space-y-1">
                            {module.module_items.map((item) => {
                                const isActive = item.id === currentItem.id;
                                return (
                                    <Link
                                        key={item.id}
                                        href={`/learn/${course.slug}/${item.id}`}
                                        onClick={onItemClick}
                                        className={`flex items-center gap-2.5 rounded-md px-2.5 py-2 text-sm transition-colors ${
                                            isActive
                                                ? 'bg-primary/10 font-medium text-primary'
                                                : 'text-foreground hover:bg-muted'
                                        }`}
                                    >
                                        <ItemIcon
                                            item={item}
                                            className={`h-4 w-4 shrink-0 ${
                                                isActive ? 'text-primary' : 'text-muted-foreground'
                                            }`}
                                        />
                                        <span className="line-clamp-1 flex-1">
                                            {item.itemable?.title ?? 'Untitled'}
                                        </span>
                                    </Link>
                                );
                            })}
                        </div>
                    </div>
                ))}
            </nav>
        </>
    );
}

/** Collapsed desktop state — icon-only rail with tooltips, current item highlighted. */
function IconRail({
    course,
    currentItem,
}: {
    course: LearningCourse;
    currentItem: LearningModuleItem;
}) {
    const allItems = course.modules.flatMap((m) => m.module_items);

    return (
        <div className="flex flex-col items-center gap-1 py-2">
            {allItems.map((item) => {
                const isActive = item.id === currentItem.id;
                return (
                    <Tooltip key={item.id}>
                        <TooltipTrigger asChild>
                            <Link
                                href={`/learn/${course.slug}/${item.id}`}
                                className={`flex h-9 w-9 items-center justify-center rounded-md transition-colors ${
                                    isActive
                                        ? 'bg-primary/10 text-primary'
                                        : 'text-muted-foreground hover:bg-muted hover:text-foreground'
                                }`}
                            >
                                <ItemIcon item={item} className="h-4 w-4" />
                            </Link>
                        </TooltipTrigger>
                        <TooltipContent side="right">{item.itemable?.title ?? 'Untitled'}</TooltipContent>
                    </Tooltip>
                );
            })}
        </div>
    );
}

export default function LearningShow() {
    const { learning } = usePage<PageProps>().props;
    const { course, enrollment, currentItem, previousItem, nextItem, statistics } = learning;

    const [mobileOpen, setMobileOpen] = useState(false);
    const [collapsed, setCollapsed] = useState(false);
    const [hydrated, setHydrated] = useState(false);

    // Read persisted collapse state after mount only, to avoid an
    // SSR/client markup mismatch (localStorage doesn't exist on the server).
    useEffect(() => {
        const stored = window.localStorage.getItem(SIDEBAR_COLLAPSED_KEY);
        if (stored === '1') setCollapsed(true);
        setHydrated(true);
    }, []);

    function toggleCollapsed() {
        setCollapsed((prev) => {
            const next = !prev;
            window.localStorage.setItem(SIDEBAR_COLLAPSED_KEY, next ? '1' : '0');
            return next;
        });
    }

    const currentTitle = currentItem.itemable?.title ?? 'Untitled';

    return (
        <>
            <Head title={`${currentTitle} — ${course.title}`} />

            <div className="min-h-screen bg-background">
                {/* Mobile top bar — sidebar collapses into a Sheet drawer below lg */}
                <div className="sticky top-0 z-20 flex items-center gap-3 border-b bg-background px-4 py-3 backdrop-blur lg:hidden">
                    <Sheet open={mobileOpen} onOpenChange={setMobileOpen}>
                        <SheetTrigger asChild>
                            <Button variant="ghost" size="icon" className="shrink-0">
                                {collapsed ? (
                                    <PanelLeftOpen className="h-4 w-4" />
                                ) : (
                                    <PanelLeftClose className="h-4 w-4" />
                                )}
                            </Button>
                        </SheetTrigger>
                        <SheetContent side="left" className="w-80 overflow-y-auto p-4">
                            <SheetHeader className="p-0 text-left">
                                <SheetTitle className="sr-only">Course content</SheetTitle>
                            </SheetHeader>
                            <SidebarNav
                                course={course}
                                enrollment={enrollment}
                                currentItem={currentItem}
                                statistics={statistics}
                                onItemClick={() => setMobileOpen(false)}
                            />
                        </SheetContent>
                    </Sheet>
                    <p className="line-clamp-1 flex-1 text-sm font-medium text-foreground">
                        {course.title}
                    </p>
                </div>

                <div className="mx-auto flex max-w-7xl items-start lg:px-4">
                    {/* Desktop sidebar — sticky, independently scrollable, collapsible to an icon rail */}
                        <aside
                            className={`hidden lg:sticky top-0 z-50 h-screen shrink-0 overflow-y-auto border-r py-6 transition-[width] duration-200 ${
                                collapsed ? 'w-16 px-2' : 'w-80 px-4'
                            } ${hydrated ? 'lg:block' : 'invisible'}`}
                        >


                        <div className="mb-3 flex justify-end">
                            <Button variant="ghost" size="icon" onClick={toggleCollapsed} className="h-8 w-8">
                                {collapsed ? (
                                    <PanelLeftOpen className="h-4 w-4" />
                                ) : (
                                    <PanelLeftClose className="h-4 w-4" />
                                )}
                            </Button>
                        </div>

                        {collapsed ? (
                            <IconRail course={course} currentItem={currentItem} />
                        ) : (
                            <SidebarNav
                                course={course}
                                enrollment={enrollment}
                                currentItem={currentItem}
                                statistics={statistics}
                            />
                        )}
                    </aside>

                    {/* Main content */}
                    <main className="min-w-0 flex-1 px-4 py-8 sm:px-6 lg:px-8">
                        <div className="mb-4 flex items-center gap-2">
                            <Badge variant="secondary" className="font-normal capitalize">
                                {contentTypeOf(currentItem)}
                            </Badge>
                            {isLesson(currentItem) && currentItem.itemable.duration_seconds > 0 && (
                                <span className="flex items-center gap-1 text-sm text-muted-foreground">
                                    <Clock className="h-3.5 w-3.5" />
                                    {formatDuration(currentItem.itemable.duration_seconds)}
                                </span>
                            )}
                        </div>

                        <h2 className="mb-4 text-2xl font-bold tracking-tight text-foreground">
                            {currentTitle}
                        </h2>

                        {isLesson(currentItem) && <LessonViewer item={currentItem} />}
                        {isAssessment(currentItem) && <AssessmentViewer item={currentItem} />}
                        {contentTypeOf(currentItem) === 'unknown' && (
                            <div className="flex items-center justify-center gap-2 rounded-lg border border-dashed py-16 text-center text-muted-foreground">
                                <Lock className="h-5 w-5" />
                            </div>
                        )}

                        {/* Prev / Next */}
                        <div className="mt-8 flex items-center justify-between border-t pt-6">
                            {previousItem ? (
                                <Button variant="outline" asChild>
                                    <Link href={`/learn/${course.slug}/${previousItem.id}`}>
                                        <ChevronLeft className="h-4 w-4" />
                                        {previousItem.itemable?.title ?? 'Previous'}
                                    </Link>
                                </Button>
                            ) : (
                                <span />
                            )}

                            {nextItem ? (
                                <Button asChild>
                                    <Link href={`/learn/${course.slug}/${nextItem.id}`}>
                                        {nextItem.itemable?.title ?? 'Next'}
                                        <ChevronRight className="h-4 w-4" />
                                    </Link>
                                </Button>
                            ) : (
                                <Button variant="outline" disabled>
                                    <CheckCircle2 className="h-4 w-4" />
                                    End of course
                                </Button>
                            )}
                        </div>
                    </main>
                </div>
            </div>
        </>
    );
}