import { ChevronLeft, ChevronRight } from 'lucide-react';
import { useCallback, useEffect, useState } from 'react';

import { Button } from '@/components/ui/button';
import {
    Carousel,
    CarouselContent,
    CarouselItem,
    type CarouselApi,
} from '@/components/ui/carousel';
import type { CourseCard } from '@/types/course';

import CourseDisplayCard from './course-card';

interface FeaturedCoursesCarouselProps {
    courses: CourseCard[];
}

export default function FeaturedCoursesCarousel({
    courses,
}: FeaturedCoursesCarouselProps) {
    const [api, setApi] = useState<CarouselApi>();
    const [current, setCurrent] = useState(0);
    const [count, setCount] = useState(0);
    const [canScrollPrevious, setCanScrollPrevious] = useState(false);
    const [canScrollNext, setCanScrollNext] = useState(false);

    const updateCarouselState = useCallback(() => {
        if (!api) {
            return;
        }

        setCurrent(api.selectedScrollSnap());
        setCount(api.scrollSnapList().length);
        setCanScrollPrevious(api.canScrollPrev());
        setCanScrollNext(api.canScrollNext());
    }, [api]);

    useEffect(() => {
        if (!api) {
            return;
        }

        updateCarouselState();

        api.on('select', updateCarouselState);
        api.on('reInit', updateCarouselState);

        return () => {
            api.off('select', updateCarouselState);
            api.off('reInit', updateCarouselState);
        };
    }, [api, updateCarouselState]);

    if (courses.length === 0) {
        return (
            <div className="rounded-2xl border border-dashed bg-muted/20 px-6 py-14 text-center">
                <p className="font-medium">No courses available yet</p>

                <p className="mt-2 text-sm text-muted-foreground">
                    Published courses will appear here.
                </p>
            </div>
        );
    }

    return (
        <div className="relative">
            {/* Navigation */}
            <div className="mb-5 hidden items-center justify-end gap-2 sm:flex">
                <Button
                    type="button"
                    variant="outline"
                    size="icon"
                    className="h-10 w-10 rounded-full"
                    onClick={() => api?.scrollPrev()}
                    disabled={!canScrollPrevious}
                    aria-label="Previous courses"
                >
                    <ChevronLeft className="h-4 w-4" />
                </Button>

                <Button
                    type="button"
                    variant="outline"
                    size="icon"
                    className="h-10 w-10 rounded-full"
                    onClick={() => api?.scrollNext()}
                    disabled={!canScrollNext}
                    aria-label="Next courses"
                >
                    <ChevronRight className="h-4 w-4" />
                </Button>
            </div>

            <Carousel
                setApi={setApi}
                opts={{
                    align: 'start',
                    loop: false,
                    skipSnaps: false,
                    dragFree: false,
                }}
                className="w-full"
            >
                <CarouselContent className="-ml-4 pb-2">
                    {courses.map((course) => (
                        <CarouselItem
                            key={course.id}
                            className="
                                basis-[88%] pl-4
                                xs:basis-[75%]
                                sm:basis-[48%]
                                lg:basis-1/3
                                xl:basis-1/4
                            "
                        >
                            <div className="h-full">
                                <CourseDisplayCard course={course} />
                            </div>
                        </CarouselItem>
                    ))}
                </CarouselContent>
            </Carousel>

            {/* Mobile controls */}
            <div className="mt-6 flex items-center justify-between sm:hidden">
                <Button
                    type="button"
                    variant="outline"
                    size="icon"
                    className="h-9 w-9 rounded-full"
                    onClick={() => api?.scrollPrev()}
                    disabled={!canScrollPrevious}
                    aria-label="Previous courses"
                >
                    <ChevronLeft className="h-4 w-4" />
                </Button>

                <div
                    className="flex max-w-[180px] items-center justify-center gap-1.5 overflow-hidden"
                    role="tablist"
                    aria-label="Course carousel navigation"
                >
                    {Array.from({ length: count }).map((_, index) => (
                        <button
                            key={index}
                            type="button"
                            role="tab"
                            aria-selected={current === index}
                            aria-label={`Go to course group ${index + 1}`}
                            onClick={() => api?.scrollTo(index)}
                            className={`h-1.5 rounded-full transition-all duration-300 ${
                                current === index
                                    ? 'w-6 bg-primary'
                                    : 'w-1.5 bg-muted-foreground/30 hover:bg-muted-foreground/50'
                            }`}
                        />
                    ))}
                </div>

                <Button
                    type="button"
                    variant="outline"
                    size="icon"
                    className="h-9 w-9 rounded-full"
                    onClick={() => api?.scrollNext()}
                    disabled={!canScrollNext}
                    aria-label="Next courses"
                >
                    <ChevronRight className="h-4 w-4" />
                </Button>
            </div>

            {/* Desktop progress */}
            {count > 1 && (
                <div className="mt-5 hidden items-center justify-between sm:flex">
                    <p className="text-sm text-muted-foreground">
                        Showing group {current + 1} of {count}
                    </p>

                    <div className="flex items-center gap-1.5">
                        {Array.from({ length: count }).map((_, index) => (
                            <button
                                key={index}
                                type="button"
                                aria-label={`Go to course group ${index + 1}`}
                                onClick={() => api?.scrollTo(index)}
                                className={`h-1.5 rounded-full transition-all duration-300 ${
                                    current === index
                                        ? 'w-6 bg-primary'
                                        : 'w-1.5 bg-muted-foreground/25 hover:bg-muted-foreground/50'
                                }`}
                            />
                        ))}
                    </div>
                </div>
            )}
        </div>
    );
}