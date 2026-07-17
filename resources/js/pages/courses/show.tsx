import { Head, Link, router, usePage } from '@inertiajs/react';
import { useState } from 'react';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Card } from '@/components/ui/card';
import { Clock, Users, Globe, PlayCircle, FileCheck, Lock } from 'lucide-react';
import { home } from '@/routes';
import courses from '@/routes/courses';

interface ModuleItem {
    id: number;
    position: number;
    itemable_type: "App\\Models\\Lesson" | 'App\\Models\\assessment' | 'unknown';
    itemable: Lesson | Assessment  ;
}
interface Lesson {
    id : number,
    title : string,
    description : string,
    type : string,
    content_url : string,
    content_text : string,
    duration_seconds : number
}
interface Assessment {
    id : number,
    title : string,
    description : string,
    type : string,
    duration_seconds : number,
    passing_score : number,
    max_attempts : number,
}

interface CourseModule {
    id: number;
    title: string;
    description: string | null;
    module_items: ModuleItem[];
}

interface Course {
    id: number;
    title: string;
    slug: string;
    description: string | null;
    thumbnail_url: string | null;
    difficulty_level: 'easy' | 'medium' | 'hard';
    duration: string;
    language: string;
    students_count?: number;
    category?: { title: string; slug: string };
    creator?: { name: string };
    modules?: CourseModule[];
}

interface Enrollment {
    status: 'active' | 'completed' | 'cancelled';
    progress_percentage: number;
}

interface PageProps {
    course: Course;
    enrollment: Enrollment | null;
    auth: { user: { id: number; name: string } | null };
    [key: string]: unknown;
}

const difficultyColor: Record<Course['difficulty_level'], string> = {
    easy: 'bg-primary/10 text-primary hover:bg-primary/10',
    medium: 'bg-amber-100 text-amber-700 hover:bg-amber-100',
    hard: 'bg-rose-100 text-rose-700 hover:bg-rose-100',
};

function formatDuration(seconds: number | null): string {
    if (!seconds) return '';
    const m = Math.round(seconds / 60);
    return `${m} min`;
}

export default function CourseShow() {
    const { course, enrollment, auth } = usePage<PageProps>().props;
    const [enrolling, setEnrolling] = useState(false);

    const isEnrolled = enrollment?.status === 'active' || enrollment?.status === 'completed';

    function handleEnroll() {
        setEnrolling(true);
        router.post(
            `/courses/${course.slug}/enroll`,
            {},
            {
                preserveScroll: true,
                onFinish: () => setEnrolling(false),
            },
        );
    }

    return (
        <>
            <Head title={course.title} />


            <div className="min-h-screen bg-background">
                <div className="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        
                    <Card className="overflow-hidden py-0">
                        
                        {course.thumbnail_url && (
                            <div className="aspect-[16/6] w-full overflow-hidden bg-muted">
                                <img
                                    src={course.thumbnail_url}
                                    alt={course.title}
                                    className="h-full w-full object-cover"
                                />
                            </div>
                        )}

                        <div className="space-y-4 p-6">
                            <div className="flex flex-wrap items-center gap-2">
                                {course.category && (
                                    <Link href={`/categories/${course.category.slug}`}>
                                        <Badge variant="secondary" className="font-normal">
                                            {course.category.title}
                                        </Badge>
                                    </Link>
                                )}
                                <Badge className={`font-normal capitalize ${difficultyColor[course.difficulty_level]}`}>
                                    {course.difficulty_level}
                                </Badge>
                                <span className="flex items-center gap-1.5 uppercase">
                                    <Globe className="h-4 w-4" />
                                    {course.language}
                                </span>
                                {/* <span className="flex items-center gap-1.5">
                                    <Clock className="h-4 w-4" />
                                    {course.duration}
                                </span> */}
                            </div>

                            <h1 className="text-3xl font-bold tracking-tight text-foreground">
                                {course.title}
                            </h1>

                            {course.creator && (
                                <p className="text-sm text-muted-foreground">
                                    Created by <span className="font-medium text-foreground">{course.creator.name}</span>
                                </p>
                            )}

                            <p className="text-foreground/90">{course.description}</p>

                            
                            <div className="pt-2">
                                {!auth.user ? (
                                    <Button asChild>
                                        <Link href="/login">Log in to enroll</Link>
                                    </Button>
                                ) : isEnrolled ? (
                                    <div className="space-y-3">
                                        <Button asChild>
                                            <Link href={`/learn/${course.slug}`}>
                                                {enrollment?.status === 'completed' ? 'Review Course' : 'Continue Learning'}
                                            </Link>
                                        </Button>
                                        {enrollment && enrollment.status === 'active' && (
                                            <div className="h-2 w-full max-w-xs overflow-hidden rounded-full bg-muted">
                                                <div
                                                    className="h-full rounded-full bg-primary transition-all"
                                                    style={{ width: `${enrollment.progress_percentage}%` }}
                                                />
                                            </div>
                                        )}
                                    </div>
                                ) : (
                                    <Button onClick={handleEnroll} disabled={enrolling}>
                                        {enrolling ? 'Enrolling…' : 'Enroll for free'}
                                    </Button>
                                )}
                            </div>
                        </div>
                    </Card>
                    
                    <div className="mt-10">
                        <h2 className="mb-4 text-xl font-semibold text-foreground">Course content</h2>

                        <div className="space-y-4">
                            {course.modules?.map((module) => (
                                <Card key={module.id} className="overflow-hidden py-0">
                                    <div className="border-b bg-muted/40 px-4 py-3">
                                        <h3 className="font-medium text-foreground">{module.title}</h3>
                                        {module.description && (
                                            <p className="mt-1 text-sm text-muted-foreground">{module.description}</p>
                                        )}
                                    </div>

                                    <ul className="divide-y">
                                        {module.module_items.map((item) => (
                                            <li
                                                key={item.itemable.id}
                                                className="flex items-center justify-between px-4 py-3 text-sm"
                                            >
                                                <div className="flex items-center gap-3">
                                                    {item.itemable_type === 'App\\Models\\assessment' ? (
                                                        <FileCheck className="h-4 w-4 text-muted-foreground" />
                                                    ) : (
                                                        <PlayCircle className="h-4 w-4 text-muted-foreground" />
                                                    )}
                                                    <span className={isEnrolled ? 'text-foreground' : 'text-muted-foreground'}>
                                                        {item.itemable.title ?? 'Untitled'}
                                                    </span>
                                                </div>

                                                <div className="flex items-center gap-3 text-muted-foreground">
                                                    {item.itemable.duration_seconds && (
                                                        <span>{formatDuration(item.itemable.duration_seconds)}</span>
                                                    )}
                                                    {!isEnrolled && <Lock className="h-3.5 w-3.5" />}
                                                </div>
                                            </li>
                                        ))}
                                    </ul>
                                </Card>
                            ))}
                        </div>
                    </div>

                </div>
            </div> 
        </>
    );
}