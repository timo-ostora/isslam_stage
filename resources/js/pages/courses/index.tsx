import { Head, Link, router, usePage } from '@inertiajs/react';
import { useEffect, useRef, useState } from 'react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Badge } from '@/components/ui/badge';
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
} from '@/components/ui/card';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Search, Users, Clock, ChevronLeft, ChevronRight } from 'lucide-react';

interface CourseCard {
    id: number;
    title: string;
    slug: string;
    description: string | null;
    thumbnail_url: string | null;
    difficulty_level: 'easy' | 'medium' | 'hard';
    duration: string;
    language: string;
    students_count: number;
    category?: { title: string; slug: string };
}

interface Category {
    id: number;
    title: string;
    slug: string;
}

interface Filters {
    search: string;
    category: string;
    difficulty: string;
    sort: 'newest' | 'title' | 'popular';
}

interface PageProps {
    courses: {
        data: CourseCard[];
        meta: {
            current_page: number;
            last_page: number;
            total: number;
            from: number | null;
            to: number | null;
        };
        links: { prev: string | null; next: string | null };
    };
    categories: Category[];
    filters: Filters;
    [key: string]: unknown;
}

const difficultyColor: Record<CourseCard['difficulty_level'], string> = {
    easy: 'bg-primary/10 text-primary hover:bg-primary/10',
    medium: 'bg-amber-100 text-amber-700 hover:bg-amber-100',
    hard: 'bg-rose-100 text-rose-700 hover:bg-rose-100',
};

export default function CourseIndex() {
    const { courses, categories, filters } = usePage<PageProps>().props;
    const courseList = Array.isArray(courses?.data) ? courses.data : [];
    const [search, setSearch] = useState(filters.search);
    const isFirstRender = useRef(true);

    function applyFilters(next: Partial<Filters>) {
        router.get(
            '/courses',
            { ...filters, ...next },
            { preserveState: true, preserveScroll: true, replace: true, only: ['courses', 'filters'] },
        );
    }

    // Debounced search — waits for the user to stop typing before hitting the server.
    useEffect(() => {
        if (isFirstRender.current) {
            isFirstRender.current = false;
            return;
        }

        const timeout = setTimeout(() => applyFilters({ search }), 400);
        return () => clearTimeout(timeout);
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [search]);

    function goToPage(url: string | null) {
        if (!url) return;
        router.get(url, {}, { preserveState: true, preserveScroll: true, only: ['courses'] });
    }

    return (
        <>
            <Head title="Course Catalog" />

            <div className="min-h-screen bg-background">
                <div className="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                    {/* Header */}
                    <div className="mb-8">
                        <h1 className="text-3xl font-bold tracking-tight text-foreground">
                            Course Catalog
                        </h1>
                        <p className="mt-1 text-muted-foreground">
                            {courses.meta.total} course{courses.meta.total === 1 ? '' : 's'} available
                        </p>
                    </div>

                    {/* Filter bar */}
                    <div className="mb-8 flex flex-col gap-3 sm:flex-row sm:items-center">
                        <div className="relative flex-1">
                            <Search className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                            <Input
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                placeholder="Search courses…"
                                className="pl-9"
                            />
                        </div>

                        <Select
                            value={filters.category || 'all'}
                            onValueChange={(value) => applyFilters({ category: value === 'all' ? '' : value })}
                        >
                            <SelectTrigger className="w-full sm:w-48">
                                <SelectValue placeholder="Category" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="all">All categories</SelectItem>
                                {categories.map((category) => (
                                    <SelectItem key={category.id} value={category.slug}>
                                        {category.title}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>

                        <Select
                            value={filters.difficulty || 'all'}
                            onValueChange={(value) => applyFilters({ difficulty: value === 'all' ? '' : value })}
                        >
                            <SelectTrigger className="w-full sm:w-40">
                                <SelectValue placeholder="Difficulty" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="all">Any difficulty</SelectItem>
                                <SelectItem value="easy">Easy</SelectItem>
                                <SelectItem value="medium">Medium</SelectItem>
                                <SelectItem value="hard">Hard</SelectItem>
                            </SelectContent>
                        </Select>

                        <Select
                            value={filters.sort}
                            onValueChange={(value) => applyFilters({ sort: value as Filters['sort'] })}
                        >
                            <SelectTrigger className="w-full sm:w-40">
                                <SelectValue placeholder="Sort by" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="newest">Newest</SelectItem>
                                <SelectItem value="popular">Most popular</SelectItem>
                                <SelectItem value="title">Title (A–Z)</SelectItem>
                            </SelectContent>
                        </Select>
                    </div>

                    {/* Grid */}
                    {courseList.length === 0 ? (
                        <div className="rounded-lg border border-dashed py-24 text-center">
                            <p className="text-muted-foreground">No courses match your filters.</p>
                        </div>
                    ) : (
                        <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            {courseList.map((course) => (
                                <Link key={course.id} href={`/courses/${course.slug}`} className="group">
                                    <Card className="h-full overflow-hidden transition-shadow hover:shadow-md pt-0">
                                        <div className="aspect-video w-full overflow-hidden bg-muted">
                                            {course.thumbnail_url && (
                                                <img
                                                    src={course.thumbnail_url}
                                                    alt={course.title}
                                                    className="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                                />
                                            )}
                                        </div>

                                        <CardHeader className="space-y-2">
                                            <div className="flex flex-wrap items-center gap-2">
                                                {course.category && (
                                                    <Badge variant="secondary" className="font-normal">
                                                        {course.category.title}
                                                    </Badge>
                                                )}
                                                <Badge className={`font-normal capitalize ${difficultyColor[course.difficulty_level]}`}>
                                                    {course.difficulty_level}
                                                </Badge>
                                            </div>

                                            <h3 className="line-clamp-2 font-semibold leading-snug text-foreground group-hover:text-primary">
                                                {course.title}
                                            </h3>
                                        </CardHeader>

                                        <CardContent>
                                            <p className="line-clamp-2 text-sm text-muted-foreground">
                                                {course.description}
                                            </p>
                                        </CardContent>

                                        <CardFooter className="flex items-center justify-between text-sm text-muted-foreground">
                                            <span className="flex items-center gap-1">
                                                <Clock className="h-3.5 w-3.5" />
                                                {course.duration}
                                            </span>
                                            <span className="flex items-center gap-1">
                                                <Users className="h-3.5 w-3.5" />
                                                {course.students_count}
                                            </span>
                                        </CardFooter>
                                    </Card>
                                </Link>
                            ))}
                        </div>
                    )}

                    {/* Pagination */}
                    {courses.meta.last_page > 1 && (
                        <div className="mt-10 flex items-center justify-between">
                            <p className="text-sm text-muted-foreground">
                                Showing {courses.meta.from}–{courses.meta.to} of {courses.meta.total}
                            </p>

                            <div className="flex items-center gap-2">
                                <Button
                                    variant="outline"
                                    size="sm"
                                    disabled={!courses.links.prev}
                                    onClick={() => goToPage(courses.links.prev)}
                                >
                                    <ChevronLeft className="h-4 w-4" />
                                    Previous
                                </Button>
                                <span className="text-sm text-muted-foreground">
                                    Page {courses.meta.current_page} of {courses.meta.last_page}
                                </span>
                                <Button
                                    variant="outline"
                                    size="sm"
                                    disabled={!courses.links.next}
                                    onClick={() => goToPage(courses.links.next)}
                                >
                                    Next
                                    <ChevronRight className="h-4 w-4" />
                                </Button>
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </>
    );
}