import { Head, router, usePage } from '@inertiajs/react';
import { home } from '@/routes';
import courses from '@/routes/courses';

import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';

import {
    ArrowRight,
    PlayCircle,
    BookOpen,
    GraduationCap,
    Trophy,
    Users,
} from 'lucide-react';

export default function Home() {
    const defaultUser = {
        name: 'User',
    };

    const user = usePage().props.auth.user || defaultUser;

    return (
        <main>
            <Head title="Home" />

            <section className="relative overflow-hidden px-8 py-16">
                <div className="mx-auto max-w-7xl">
                    <div className="grid gap-12 lg:grid-cols-2 lg:items-center">
                        {/* Left */}
                        <div className="space-y-8">

                            <div className="space-y-4">
                                <Badge variant="secondary" className="w-fit">
                                    🚀 Learn at your own pace
                                </Badge>
                                <h1 className="max-w-2xl text-4xl font-bold tracking-tight lg:text-6xl">
                                    Master Skills That
                                    <span className="block">
                                        Move Your Career Forward
                                    </span>
                                </h1>

                                <p className="max-w-xl text-lg text-muted-foreground">
                                    Discover expertly crafted courses designed to
                                    help you build practical skills, complete real
                                    projects, and earn certificates that showcase
                                    your expertise.
                                </p>
                            </div>

                            <div className="flex flex-wrap gap-3">
                                <Button
                                    size="lg"
                                    onClick={() =>
                                        router.visit(courses.index())
                                    }
                                >
                                    Browse Courses
                                    <ArrowRight className="ml-2 h-4 w-4" />
                                </Button>

                                <Button
                                    variant="outline"
                                    size="lg"
                                >
                                    <Users className="mr-2 h-4 w-4" />
                                    Meet Our Instructors
                                </Button>
                            </div>

                            <div className="flex flex-wrap gap-8 pt-6">
                                <div>
                                    <p className="text-2xl font-bold">250+</p>
                                    <p className="text-sm text-muted-foreground">
                                        Premium Courses
                                    </p>
                                </div>

                                <div>
                                    <p className="text-2xl font-bold">12k+</p>
                                    <p className="text-sm text-muted-foreground">
                                        Active Students
                                    </p>
                                </div>

                                <div>
                                    <p className="text-2xl font-bold">98%</p>
                                    <p className="text-sm text-muted-foreground">
                                        Course Completion
                                    </p>
                                </div>
                            </div>
                        </div>

                        {/* Right */}
                        <div className="grid gap-4 sm:grid-cols-2">
                            <div className="rounded-xl border bg-card p-6">
                                <BookOpen className="mb-4 h-8 w-8" />

                                <h3 className="font-semibold">
                                    Hands-on Learning
                                </h3>

                                <p className="mt-2 text-sm text-muted-foreground">
                                    Build practical experience with projects,
                                    quizzes, and real-world exercises.
                                </p>
                            </div>

                            <div className="rounded-xl border bg-card p-6">
                                <GraduationCap className="mb-4 h-8 w-8" />

                                <h3 className="font-semibold">
                                    Expert Instructors
                                </h3>

                                <p className="mt-2 text-sm text-muted-foreground">
                                    Learn directly from experienced professionals
                                    who work in the industry.
                                </p>
                            </div>

                            <div className="rounded-xl border bg-card p-6 sm:col-span-2">
                                <Trophy className="mb-4 h-8 w-8" />

                                <h3 className="font-semibold">
                                    Earn Recognized Certificates
                                </h3>

                                <p className="mt-2 text-sm text-muted-foreground">
                                    Complete courses, track your progress, and
                                    showcase your achievements with shareable
                                    certificates.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
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