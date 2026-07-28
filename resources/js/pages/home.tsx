import { Head, Link, usePage } from '@inertiajs/react';
import {
    ArrowRight,
    Award,
    BookOpen,
    Check,
    CheckCircle2,
    Clock3,
    GraduationCap,
    Heart,
    Laptop,
    MessageCircle,
    Play,
    Quote,
    ShieldCheck,
    Sparkles,
    Star,
    Target,
    Trophy,
    UserCheck,
    Users,
} from 'lucide-react';

import CoursesCarousel from '@/components/course/courses-carousel';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { home } from '@/routes';
import courses from '@/routes/courses';
import type { CourseCard } from '@/types/course';

interface Expert {
    id: number;
    name: string;
    avatar_url?: string | null;
    courses_count: number;
    students_count: number;
}

interface PlatformStats {
    courses: number;
    students: number;
    experts: number;
}

interface HomeData {
    popularCourses: CourseCard[];
    experts: Expert[];
    stats: PlatformStats;
}

interface PageProps {
    data: HomeData;
    [key: string]: unknown;
}

interface Benefit {
    icon: typeof BookOpen;
    title: string;
    description: string;
}

interface Testimonial {
    name: string;
    role: string;
    content: string;
    initials: string;
    rating: number;
}

const benefits: Benefit[] = [
    {
        icon: BookOpen,
        title: 'Structured learning',
        description:
            'Follow clear lessons organized into modules so you always know what to learn next.',
    },
    {
        icon: Laptop,
        title: 'Learn anywhere',
        description:
            'Access your courses from your computer, tablet, or phone and continue at your own pace.',
    },
    {
        icon: UserCheck,
        title: 'Expert instructors',
        description:
            'Learn from experienced instructors who make difficult subjects easier to understand.',
    },
    {
        icon: Trophy,
        title: 'Track your progress',
        description:
            'Complete lessons, follow your progress, and stay motivated throughout your learning journey.',
    },
    {
        icon: Award,
        title: 'Earn certificates',
        description:
            'Showcase your completed courses and demonstrate the skills you have developed.',
    },
    {
        icon: MessageCircle,
        title: 'Support when needed',
        description:
            'Get guidance and clear learning resources whenever you encounter a difficult topic.',
    },
];

const learningSteps = [
    {
        number: '01',
        icon: Target,
        title: 'Choose your goal',
        description:
            'Explore available courses and select the skill or subject you want to master.',
    },
    {
        number: '02',
        icon: Play,
        title: 'Start learning',
        description:
            'Follow structured modules, practical lessons, and exercises at your own pace.',
    },
    {
        number: '03',
        icon: CheckCircle2,
        title: 'Complete activities',
        description:
            'Test your knowledge, complete course content, and monitor your progress.',
    },
    {
        number: '04',
        icon: Trophy,
        title: 'Reach your goals',
        description:
            'Apply your new knowledge and celebrate your learning achievements.',
    },
];

const testimonials: Testimonial[] = [
    {
        name: 'Sarah Martin',
        role: 'Web development student',
        content:
            'The lessons were easy to follow and helped me understand concepts that previously felt complicated.',
        initials: 'SM',
        rating: 5,
    },
    {
        name: 'Adam Wilson',
        role: 'Business student',
        content:
            'I really appreciated being able to learn at my own pace while tracking my progress after each lesson.',
        initials: 'AW',
        rating: 5,
    },
    {
        name: 'Nora James',
        role: 'Design student',
        content:
            'The course structure and practical explanations made the learning experience clear and motivating.',
        initials: 'NJ',
        rating: 5,
    },
];

function formatNumber(value: number): string {
    return new Intl.NumberFormat('en-US', {
        notation: value >= 1000 ? 'compact' : 'standard',
        maximumFractionDigits: 1,
    }).format(value);
}

function getInitials(name: string): string {
    return name
        .split(' ')
        .filter(Boolean)
        .slice(0, 2)
        .map((part) => part.charAt(0).toUpperCase())
        .join('');
}

export default function Home() {
    const { data } = usePage<PageProps>().props;

    const popularCourses = data?.popularCourses ?? [];
    const experts = data?.experts ?? [];

    const stats = data?.stats ?? {
        courses: 0,
        students: 0,
        experts: 0,
    };

    return (
        <>
            <Head title="Learn new skills and grow your future" />

            <main className="overflow-hidden">
                {/* 1. Hero section */}
                <section className="relative isolate overflow-hidden border-b">
                    <div className="absolute inset-0 -z-20 bg-background" />

                    <div className="absolute left-1/2 top-0 -z-10 h-[650px] w-[650px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-primary/10 blur-3xl" />

                    <div className="absolute -bottom-40 -right-40 -z-10 h-[500px] w-[500px] rounded-full bg-primary/5 blur-3xl" />

                    <div className="mx-auto grid min-h-[720px] max-w-7xl items-center gap-16 px-4 py-16 sm:px-6 lg:grid-cols-2 lg:px-8 lg:py-24">
                        <div className="max-w-3xl">
                            <Badge
                                variant="secondary"
                                className="mb-6 rounded-full px-4 py-1.5"
                            >
                                <Sparkles className="mr-2 h-4 w-4" />
                                Build skills for a better future
                            </Badge>

                            <h1 className="text-balance text-4xl font-bold tracking-tight sm:text-5xl lg:text-7xl">
                                Learn today.
                                <span className="block text-primary">
                                    Grow tomorrow.
                                </span>
                            </h1>

                            <p className="mt-6 max-w-2xl text-lg leading-8 text-muted-foreground sm:text-xl">
                                Discover structured courses created to help you
                                build practical skills, grow your knowledge, and
                                confidently move toward your goals.
                            </p>

                            <div className="mt-8 flex flex-col gap-3 sm:flex-row">
                                <Button
                                    size="lg"
                                    className="h-12 px-6"
                                    asChild
                                >
                                    <Link href={courses.index()}>
                                        Start learning
                                        <ArrowRight className="ml-2 h-4 w-4" />
                                    </Link>
                                </Button>

                                <Button
                                    variant="outline"
                                    size="lg"
                                    className="h-12 px-6"
                                    asChild
                                >
                                    <a href="#about">
                                        <Heart className="mr-2 h-4 w-4" />
                                        Learn about us
                                    </a>
                                </Button>
                            </div>

                            <div className="mt-9 flex flex-wrap gap-x-7 gap-y-3">
                                {[
                                    'Learn at your own pace',
                                    'Experienced instructors',
                                    'Progress tracking',
                                ].map((item) => (
                                    <div
                                        key={item}
                                        className="flex items-center gap-2 text-sm font-medium"
                                    >
                                        <CheckCircle2 className="h-4 w-4 text-primary" />
                                        {item}
                                    </div>
                                ))}
                            </div>
                        </div>

                        <div className="relative">
                            <div className="absolute -inset-8 -z-10 rounded-full bg-primary/10 blur-3xl" />

                            <div className="relative overflow-hidden rounded-3xl border bg-card p-5 shadow-2xl">
                                <div className="rounded-2xl border bg-muted/40 p-6 sm:p-8">
                                    <div className="flex items-center justify-between">
                                        <div>
                                            <p className="text-sm text-muted-foreground">
                                                Your learning journey
                                            </p>

                                            <h2 className="mt-1 text-xl font-semibold">
                                                Continue growing every day
                                            </h2>
                                        </div>

                                        <div className="flex h-12 w-12 items-center justify-center rounded-2xl bg-primary text-primary-foreground">
                                            <GraduationCap className="h-6 w-6" />
                                        </div>
                                    </div>

                                    <div className="mt-8 space-y-5">
                                        <div className="rounded-xl border bg-background p-4">
                                            <div className="flex items-center justify-between">
                                                <div className="flex items-center gap-3">
                                                    <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary">
                                                        <Play className="h-5 w-5" />
                                                    </div>

                                                    <div>
                                                        <p className="font-medium">
                                                            Practical lessons
                                                        </p>
                                                        <p className="text-xs text-muted-foreground">
                                                            Learn through clear
                                                            examples
                                                        </p>
                                                    </div>
                                                </div>

                                                <Check className="h-5 w-5 text-primary" />
                                            </div>
                                        </div>

                                        <div className="rounded-xl border bg-background p-4">
                                            <div className="flex items-center justify-between">
                                                <div className="flex items-center gap-3">
                                                    <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary">
                                                        <Clock3 className="h-5 w-5" />
                                                    </div>

                                                    <div>
                                                        <p className="font-medium">
                                                            Flexible schedule
                                                        </p>
                                                        <p className="text-xs text-muted-foreground">
                                                            Study when it works
                                                            for you
                                                        </p>
                                                    </div>
                                                </div>

                                                <Check className="h-5 w-5 text-primary" />
                                            </div>
                                        </div>

                                        <div className="rounded-xl border bg-background p-4">
                                            <div className="flex items-center justify-between">
                                                <div className="flex items-center gap-3">
                                                    <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary">
                                                        <Award className="h-5 w-5" />
                                                    </div>

                                                    <div>
                                                        <p className="font-medium">
                                                            Real achievements
                                                        </p>
                                                        <p className="text-xs text-muted-foreground">
                                                            Complete courses and
                                                            grow
                                                        </p>
                                                    </div>
                                                </div>

                                                <Check className="h-5 w-5 text-primary" />
                                            </div>
                                        </div>
                                    </div>

                                    <div className="mt-6 rounded-xl bg-primary p-5 text-primary-foreground">
                                        <div className="flex items-center justify-between">
                                            <div>
                                                <p className="text-sm text-primary-foreground/75">
                                                    Ready to begin?
                                                </p>
                                                <p className="mt-1 font-semibold">
                                                    Find your next course
                                                </p>
                                            </div>

                                            <Button
                                                variant="secondary"
                                                size="icon"
                                                className="rounded-full"
                                                asChild
                                            >
                                                <Link href={courses.index()}>
                                                    <ArrowRight className="h-4 w-4" />
                                                </Link>
                                            </Button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {/* 2. Statistics section */}
                <section className="border-b bg-muted/20">
                    <div className="mx-auto grid max-w-7xl grid-cols-1 divide-y px-4 sm:grid-cols-3 sm:divide-x sm:divide-y-0 sm:px-6 lg:px-8">
                        <div className="flex items-center gap-4 py-8 sm:justify-center sm:px-6">
                            <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                <BookOpen className="h-6 w-6" />
                            </div>

                            <div>
                                <p className="text-3xl font-bold">
                                    {formatNumber(stats.courses)}+
                                </p>
                                <p className="text-sm text-muted-foreground">
                                    Published courses
                                </p>
                            </div>
                        </div>

                        <div className="flex items-center gap-4 py-8 sm:justify-center sm:px-6">
                            <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                <Users className="h-6 w-6" />
                            </div>

                            <div>
                                <p className="text-3xl font-bold">
                                    {formatNumber(stats.students)}+
                                </p>
                                <p className="text-sm text-muted-foreground">
                                    Active learners
                                </p>
                            </div>
                        </div>

                        <div className="flex items-center gap-4 py-8 sm:justify-center sm:px-6">
                            <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                <GraduationCap className="h-6 w-6" />
                            </div>

                            <div>
                                <p className="text-3xl font-bold">
                                    {formatNumber(stats.experts)}+
                                </p>
                                <p className="text-sm text-muted-foreground">
                                    Expert instructors
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                                {/* 4. Popular courses section */}
                <section className="border-y bg-muted/20 px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
                    <div className="mx-auto max-w-7xl">
                        <div className="mb-10 flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
                            <div className="max-w-2xl">
                                <Badge variant="secondary" className="mb-4">
                                    Popular courses
                                </Badge>

                                <h2 className="text-3xl font-bold tracking-tight sm:text-4xl">
                                    Learn what students love most
                                </h2>

                                <p className="mt-4 text-lg leading-8 text-muted-foreground">
                                    Explore popular courses selected by learners
                                    who are already building new skills.
                                </p>
                            </div>

                            <Button variant="outline" asChild>
                                <Link href={courses.index()}>
                                    View all courses
                                    <ArrowRight className="ml-2 h-4 w-4" />
                                </Link>
                            </Button>
                        </div>

                        {popularCourses.length > 0 ? (
                            <CoursesCarousel courses={popularCourses} />
                        ) : (
                            <div className="rounded-2xl border border-dashed bg-background px-6 py-16 text-center">
                                <BookOpen className="mx-auto h-10 w-10 text-muted-foreground" />

                                <h3 className="mt-4 font-semibold">
                                    Courses are coming soon
                                </h3>

                                <p className="mt-2 text-sm text-muted-foreground">
                                    Published courses will be displayed here.
                                </p>
                            </div>
                        )}
                    </div>
                </section>

                {/* 3. Benefits section */}
                <section className="px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
                    <div className="mx-auto max-w-7xl">
                        <div className="mx-auto mb-12 max-w-3xl text-center">
                            <Badge variant="secondary" className="mb-4">
                                Why choose us
                            </Badge>

                            <h2 className="text-3xl font-bold tracking-tight sm:text-4xl">
                                Everything you need to learn confidently
                            </h2>

                            <p className="mt-4 text-lg leading-8 text-muted-foreground">
                                Our platform gives you a simple, structured, and
                                motivating way to develop valuable knowledge and
                                practical skills.
                            </p>
                        </div>

                        <div className="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                            {benefits.map((benefit) => {
                                const Icon = benefit.icon;

                                return (
                                    <Card
                                        key={benefit.title}
                                        className="group border-border/70 transition-all duration-300 hover:-translate-y-1 hover:border-primary/30 hover:shadow-lg"
                                    >
                                        <CardContent className="p-6">
                                            <div className="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-primary/10 text-primary transition-colors group-hover:bg-primary group-hover:text-primary-foreground">
                                                <Icon className="h-6 w-6" />
                                            </div>

                                            <h3 className="text-lg font-semibold">
                                                {benefit.title}
                                            </h3>

                                            <p className="mt-3 text-sm leading-6 text-muted-foreground">
                                                {benefit.description}
                                            </p>
                                        </CardContent>
                                    </Card>
                                );
                            })}
                        </div>
                    </div>
                </section>



                {/* 5. How it works section */}
                <section className="px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
                    <div className="mx-auto max-w-7xl">
                        <div className="mx-auto mb-14 max-w-3xl text-center">
                            <Badge variant="secondary" className="mb-4">
                                Simple learning journey
                            </Badge>

                            <h2 className="text-3xl font-bold tracking-tight sm:text-4xl">
                                Start learning in four simple steps
                            </h2>

                            <p className="mt-4 text-lg text-muted-foreground">
                                You do not need a complicated plan. Choose your
                                course and move forward one lesson at a time.
                            </p>
                        </div>

                        <div className="grid gap-5 md:grid-cols-2 lg:grid-cols-4">
                            {learningSteps.map((step, index) => {
                                const Icon = step.icon;

                                return (
                                    <div
                                        key={step.number}
                                        className="relative rounded-2xl border bg-card p-6"
                                    >
                                        {index < learningSteps.length - 1 && (
                                            <div className="absolute left-full top-12 hidden h-px w-5 bg-border lg:block" />
                                        )}

                                        <div className="flex items-center justify-between">
                                            <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-primary text-primary-foreground">
                                                <Icon className="h-6 w-6" />
                                            </div>

                                            <span className="text-3xl font-bold text-muted-foreground/20">
                                                {step.number}
                                            </span>
                                        </div>

                                        <h3 className="mt-6 text-lg font-semibold">
                                            {step.title}
                                        </h3>

                                        <p className="mt-3 text-sm leading-6 text-muted-foreground">
                                            {step.description}
                                        </p>
                                    </div>
                                );
                            })}
                        </div>
                    </div>
                </section>

                {/* 6. Experts section */}
                <section
                    id="instructors"
                    className="border-y bg-muted/20 px-4 py-20 sm:px-6 lg:px-8 lg:py-24"
                >
                    <div className="mx-auto max-w-7xl">
                        <div className="mb-12 max-w-3xl">
                            <Badge variant="secondary" className="mb-4">
                                Our instructors
                            </Badge>

                            <h2 className="text-3xl font-bold tracking-tight sm:text-4xl">
                                Learn from people who care about your progress
                            </h2>

                            <p className="mt-4 text-lg leading-8 text-muted-foreground">
                                Our instructors organize their knowledge into
                                clear courses designed to help you understand,
                                practice, and improve.
                            </p>
                        </div>

                        {experts.length > 0 ? (
                            <div className="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                                {experts.slice(0, 6).map((expert) => (
                                    <Card
                                        key={expert.id}
                                        className="group overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
                                    >
                                        <CardContent className="p-6">
                                            <div className="flex items-center gap-4">
                                                <div className="h-16 w-16 shrink-0 overflow-hidden rounded-full border bg-primary/10">
                                                    {expert.avatar_url ? (
                                                        <img
                                                            src={
                                                                expert.avatar_url
                                                            }
                                                            alt={expert.name}
                                                            className="h-full w-full object-cover"
                                                            loading="lazy"
                                                        />
                                                    ) : (
                                                        <div className="flex h-full w-full items-center justify-center text-lg font-bold text-primary">
                                                            {getInitials(
                                                                expert.name,
                                                            )}
                                                        </div>
                                                    )}
                                                </div>

                                                <div className="min-w-0">
                                                    <h3 className="truncate text-lg font-semibold">
                                                        {expert.name}
                                                    </h3>

                                                    <p className="text-sm text-primary">
                                                        Expert instructor
                                                    </p>
                                                </div>
                                            </div>

                                            <p className="mt-5 text-sm leading-6 text-muted-foreground">
                                                Helping learners understand
                                                important subjects through
                                                organized courses and practical
                                                explanations.
                                            </p>

                                            <div className="mt-6 grid grid-cols-2 divide-x rounded-xl border bg-muted/30 py-4 text-center">
                                                <div className="px-3">
                                                    <BookOpen className="mx-auto mb-2 h-4 w-4 text-muted-foreground" />
                                                    <p className="font-semibold">
                                                        {expert.courses_count}
                                                    </p>
                                                    <p className="text-xs text-muted-foreground">
                                                        Courses
                                                    </p>
                                                </div>

                                                <div className="px-3">
                                                    <Users className="mx-auto mb-2 h-4 w-4 text-muted-foreground" />
                                                    <p className="font-semibold">
                                                        {formatNumber(
                                                            expert.students_count,
                                                        )}
                                                    </p>
                                                    <p className="text-xs text-muted-foreground">
                                                        Enrollments
                                                    </p>
                                                </div>
                                            </div>
                                        </CardContent>
                                    </Card>
                                ))}
                            </div>
                        ) : (
                            <div className="rounded-2xl border border-dashed bg-background px-6 py-16 text-center">
                                <GraduationCap className="mx-auto h-10 w-10 text-muted-foreground" />

                                <h3 className="mt-4 font-semibold">
                                    Instructors will appear here
                                </h3>
                            </div>
                        )}
                    </div>
                </section>

                {/* 7. About section */}
                <section
                    id="about"
                    className="px-4 py-20 sm:px-6 lg:px-8 lg:py-24"
                >
                    <div className="mx-auto grid max-w-7xl gap-12 lg:grid-cols-2 lg:items-center">
                        <div className="relative">
                            <div className="absolute -inset-6 -z-10 rounded-full bg-primary/10 blur-3xl" />

                            <div className="grid grid-cols-2 gap-4">
                                <Card className="translate-y-8">
                                    <CardContent className="p-6">
                                        <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                            <Heart className="h-6 w-6" />
                                        </div>

                                        <p className="mt-6 text-3xl font-bold">
                                            Learner first
                                        </p>

                                        <p className="mt-3 text-sm leading-6 text-muted-foreground">
                                            Every course experience is designed
                                            around clarity, progress, and
                                            confidence.
                                        </p>
                                    </CardContent>
                                </Card>

                                <Card>
                                    <CardContent className="p-6">
                                        <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                            <ShieldCheck className="h-6 w-6" />
                                        </div>

                                        <p className="mt-6 text-3xl font-bold">
                                            Quality focused
                                        </p>

                                        <p className="mt-3 text-sm leading-6 text-muted-foreground">
                                            We focus on useful content that
                                            helps learners build real
                                            understanding.
                                        </p>
                                    </CardContent>
                                </Card>

                                <Card className="col-span-2 mt-8">
                                    <CardContent className="flex items-center gap-5 p-6">
                                        <div className="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-primary text-primary-foreground">
                                            <Users className="h-7 w-7" />
                                        </div>

                                        <div>
                                            <p className="font-semibold">
                                                A growing learning community
                                            </p>

                                            <p className="mt-1 text-sm text-muted-foreground">
                                                Students and instructors moving
                                                forward together.
                                            </p>
                                        </div>
                                    </CardContent>
                                </Card>
                            </div>
                        </div>

                        <div>
                            <Badge variant="secondary" className="mb-4">
                                About us
                            </Badge>

                            <h2 className="text-3xl font-bold tracking-tight sm:text-4xl">
                                We believe learning should feel possible
                            </h2>

                            <p className="mt-6 text-lg leading-8 text-muted-foreground">
                                Our mission is to make useful knowledge easier
                                to access, understand, and apply. We connect
                                learners with structured courses and
                                instructors who want to help them succeed.
                            </p>

                            <p className="mt-4 leading-7 text-muted-foreground">
                                Whether you are learning for your studies, your
                                career, or your personal growth, our platform is
                                built to help you move forward with confidence.
                            </p>

                            <div className="mt-8 space-y-4">
                                {[
                                    'Clear and organized course content',
                                    'Flexible learning for different schedules',
                                    'A focus on practical understanding',
                                    'A supportive learning experience',
                                ].map((item) => (
                                    <div
                                        key={item}
                                        className="flex items-center gap-3"
                                    >
                                        <div className="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-primary/10 text-primary">
                                            <Check className="h-3.5 w-3.5" />
                                        </div>

                                        <span className="text-sm font-medium">
                                            {item}
                                        </span>
                                    </div>
                                ))}
                            </div>

                            <Button className="mt-8" variant="outline" asChild>
                                <Link href={courses.index()}>
                                    Explore our courses
                                    <ArrowRight className="ml-2 h-4 w-4" />
                                </Link>
                            </Button>
                        </div>
                    </div>
                </section>

                {/* 8. Testimonials section */}
                <section className="border-y bg-muted/20 px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
                    <div className="mx-auto max-w-7xl">
                        <div className="mx-auto mb-12 max-w-3xl text-center">
                            <Badge variant="secondary" className="mb-4">
                                Student experiences
                            </Badge>

                            <h2 className="text-3xl font-bold tracking-tight sm:text-4xl">
                                Learning that makes a difference
                            </h2>

                            <p className="mt-4 text-lg text-muted-foreground">
                                See how a clear and flexible learning experience
                                can help students stay motivated.
                            </p>
                        </div>

                        <div className="grid gap-5 md:grid-cols-3">
                            {testimonials.map((testimonial) => (
                                <Card
                                    key={testimonial.name}
                                    className="relative"
                                >
                                    <CardHeader>
                                        <div className="flex items-start justify-between">
                                            <Quote className="h-9 w-9 text-primary/20" />

                                            <div className="flex gap-1">
                                                {Array.from({
                                                    length: testimonial.rating,
                                                }).map((_, index) => (
                                                    <Star
                                                        key={index}
                                                        className="h-4 w-4 fill-primary text-primary"
                                                    />
                                                ))}
                                            </div>
                                        </div>
                                    </CardHeader>

                                    <CardContent>
                                        <p className="min-h-[96px] leading-7 text-muted-foreground">
                                            “{testimonial.content}”
                                        </p>

                                        <div className="mt-6 flex items-center gap-3 border-t pt-5">
                                            <div className="flex h-11 w-11 items-center justify-center rounded-full bg-primary/10 text-sm font-bold text-primary">
                                                {testimonial.initials}
                                            </div>

                                            <div>
                                                <p className="font-semibold">
                                                    {testimonial.name}
                                                </p>

                                                <p className="text-sm text-muted-foreground">
                                                    {testimonial.role}
                                                </p>
                                            </div>
                                        </div>
                                    </CardContent>
                                </Card>
                            ))}
                        </div>
                    </div>
                </section>

                {/* 9. Final CTA section */}
                <section className="px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
                    <div className="mx-auto max-w-7xl overflow-hidden rounded-3xl bg-primary px-6 py-12 text-primary-foreground shadow-xl sm:px-12 lg:flex lg:items-center lg:justify-between lg:gap-12">
                        <div className="max-w-2xl">
                            <Badge
                                variant="secondary"
                                className="mb-5 rounded-full"
                            >
                                Your next step starts here
                            </Badge>

                            <h2 className="text-3xl font-bold tracking-tight sm:text-4xl">
                                Start building the future you want
                            </h2>

                            <p className="mt-4 text-lg leading-8 text-primary-foreground/80">
                                Explore the available courses, choose your next
                                goal, and begin learning at your own pace today.
                            </p>
                        </div>

                        <div className="mt-8 flex flex-col gap-3 sm:flex-row lg:mt-0">
                            <Button
                                size="lg"
                                variant="secondary"
                                className="h-12 px-6"
                                asChild
                            >
                                <Link href={courses.index()}>
                                    Browse courses
                                    <ArrowRight className="ml-2 h-4 w-4" />
                                </Link>
                            </Button>

                            {/* <Button
                                size="lg"
                                variant="outline"
                                className="h-12 border-primary-foreground/30 bg-transparent px-6 text-primary-foreground hover:bg-primary-foreground/10 hover:text-primary-foreground"
                                asChild
                            >
                                <a href="#about">Learn about us</a>
                            </Button> */}
                        </div>
                    </div>
                </section>
            </main>
        </>
    );
}

Home.layout = {
    breadcrumbs: [
        {
            title: 'Home',
            href: home(),
        },
    ],
};