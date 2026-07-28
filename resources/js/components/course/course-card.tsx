import type { CourseCard, Course } from '@/types/course';
import { Link } from "@inertiajs/react"
import { Card, CardContent, CardFooter, CardHeader } from "../ui/card"
import { Badge } from "../ui/badge"
import { Clock, Users } from "lucide-react"

const difficultyColor: Record<Course['difficulty_level'], string> = {
    easy: 'bg-primary/10 text-primary hover:bg-primary/10',
    medium: 'bg-amber-100 text-amber-700 hover:bg-amber-100',
    hard: 'bg-rose-100 text-rose-700 hover:bg-rose-100',
};
interface CourseCardProps {
    course: CourseCard;
}
export default function CourseDisplayCard ({course}: CourseCardProps ) {
  return (
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
  )
}