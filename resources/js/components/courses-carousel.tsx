// components/FeaturedCoursesCarousel.tsx
import { useEffect, useState } from 'react';
import { Link, router } from '@inertiajs/react';
import {
  Carousel,
  CarouselContent,
  CarouselItem,
  type CarouselApi,
} from '@/components/ui/carousel';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { ChevronLeft, ChevronRight, Clock, Star, Users } from 'lucide-react';
import { CourseCard, Course } from '@/types/course';
import { Card, CardContent, CardFooter, CardHeader } from './ui/card';
interface CourseCardData {
  id: number;
  title: string;
  slug: string;
  thumbnail: string | null;
  level: 'beginner' | 'intermediate' | 'advanced';
  category: string | null;
  students_count: number;
  rating: number;
  creator: {
    name: string;
  };
}

const difficultyColor: Record<Course['difficulty_level'], string> = {
    easy: 'bg-primary/10 text-primary hover:bg-primary/10',
    medium: 'bg-amber-100 text-amber-700 hover:bg-amber-100',
    hard: 'bg-rose-100 text-rose-700 hover:bg-rose-100',
};

export default function FeaturedCoursesCarousel({ courses }: { courses: CourseCard[] }) {
  const [api, setApi] = useState<CarouselApi>();
  const [current, setCurrent] = useState(0);
  const [count, setCount] = useState(0);

useEffect(() => {
  if (!api) return;
  console.log('api ready', api); // debug
  setCount(api.scrollSnapList().length);
  setCurrent(api.selectedScrollSnap());
  api.on('select', () => setCurrent(api.selectedScrollSnap()));
}, [api]);

  return (
    <section className="py-12">
      <div className="flex items-center justify-between mb-6">
        <h2 className="text-2xl font-semibold text-foreground">Cours à la une</h2>

        <div className="flex items-center gap-4">
          <button
            onClick={() => router.visit('/courses')}
            className="text-sm text-primary hover:underline"
          >
            Voir tous les cours
          </button>

          <div className="hidden sm:flex items-center gap-2">
            <Button
              variant="outline"
              size="icon"
              className="h-8 w-8"
              onClick={() => api?.scrollPrev()}
              disabled={!api?.canScrollPrev()}
            >
              <ChevronLeft className="h-4 w-4" />
            </Button>
            <Button
              variant="outline"
              size="icon"
              className="h-8 w-8"
              onClick={() => api?.scrollNext()}
              disabled={!api?.canScrollNext()}
            >
              <ChevronRight className="h-4 w-4" />
            </Button>
          </div>
        </div>
      </div>

      <Carousel setApi={setApi} opts={{ align: 'start', loop: false }} className="w-full">
        <CarouselContent className="-ml-4">
          {courses.map((course) => (
            <CarouselItem
              key={course.id}
              className="pl-4 basis-[85%] sm:basis-[40%] lg:basis-[28%]"
            >
              {/* <div
                onClick={() => router.visit(`/courses/${course.slug}`)}
                role="button"
                tabIndex={0}
                className="group block h-full rounded-lg border bg-card text-card-foreground overflow-hidden hover:border-primary transition-colors cursor-pointer"
              >
                <div className="relative aspect-video overflow-hidden">
                  <img
                    src={course.thumbnail_url ?? '/placeholder-course.jpg'}
                    alt={course.title}
                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                  />
                  <Badge className="absolute top-2 left-2 capitalize">
                    {course.level}
                  </Badge>
                </div>

                <div className="p-4 space-y-2">
                  {course.category && (
                    <span className="text-xs uppercase tracking-wide text-muted-foreground">
                      {course.category}
                    </span>
                  )}

                  <h3 className="font-semibold leading-snug line-clamp-2 text-foreground">
                    {course.title}
                  </h3>

                  <p className="text-sm text-muted-foreground">{course.creator.name}</p>

                  <div className="flex items-center justify-between pt-2 text-sm text-muted-foreground">
                    <span className="flex items-center gap-1">
                      <Star className="h-4 w-4 fill-primary text-primary" />
                      {course.rating}
                    </span>
                    <span className="flex items-center gap-1">
                      <Users className="h-4 w-4" />
                      {course.students_count}
                    </span>
                  </div>
                </div>
              </div> */}
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
                          <div className="flex flex-wrap items-center gap-2">
                              {course.category && (
                                  <Badge variant="secondary" className="font-normal">
                                      {course.category.title}
                                  </Badge>
                              )}
                              <Badge className={`font-normal capitalize ${difficultyColor[course.difficulty_level]}`}>
                                  {course.difficulty_level}
                              </Badge>
                              <span className="flex items-center gap-1">
                                  <Clock className="h-3.5 w-3.5" />
                                  {course.duration}
                              </span>
                          </div>
                          <span className="flex items-center gap-1">
                              <Users className="h-3.5 w-3.5" />
                              {course.students_count}
                          </span>
                      </CardFooter>
                  </Card>
              </Link>
            </CarouselItem>
          ))}
        </CarouselContent>
      </Carousel>

      {/* Dots — mobile only */}
      <div className="flex sm:hidden justify-center gap-1.5 mt-4">
        {Array.from({ length: count }).map((_, index) => (
          <button
            key={index}
            onClick={() => api?.scrollTo(index)}
            className={`h-1.5 rounded-full transition-all ${
              index === current ? 'w-4 bg-primary' : 'w-1.5 bg-muted'
            }`}
            aria-label={`Aller à la card ${index + 1}`}
          />
        ))}
      </div>
    </section>
  );
}