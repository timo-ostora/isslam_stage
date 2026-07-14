// import { Head, router, usePage} from '@inertiajs/react';
// import { home } from '@/routes';
// import { Button } from '@/components/ui/button';
// import { Badge } from '@/components/ui/badge';
// import { BookOpen, Clock} from 'lucide-react';
// import { Card, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
// import { formatDuration } from '@/lib/utils';

// export default function CategoryIndex({ categories }: { categories: any }) {

//   // Helper to pick difficulty badge colors
//   const getDifficultyColor = (level: string) => {
//       switch (level) {
//           case 'easy': return 'bg-emerald-500/10 text-emerald-500 hover:bg-emerald-500/10';
//           case 'medium': return 'bg-amber-500/10 text-amber-500 hover:bg-amber-500/10';
//           case 'hard': return 'bg-rose-500/10 text-rose-500 hover:bg-rose-500/10';
//           default: return 'bg-slate-500/10 text-slate-500';
//       }
//   };
//   return (
//       <>
//         <Head title="Home" />

//         <div className="pt-6 mb-10 text-center md:text-left">
//                 <h1 className="text-4xl font-extrabold tracking-tight lg:text-5xl">
//                     Course Catalog
//                 </h1>
//                 <p className="mt-2 text-muted-foreground text-lg">
//                     Browse our structured tracks and enhance your skill sets.
//                 </p>
//             </div>

//             {/* Categories Loop */}
//             <div className="space-y-12">
//                 {categories.map((category: any) => {
//                     // Skip categories without courses for a cleaner MVP presentation
//                     if (!category.courses || category.courses.length === 0) return null;

//                     return (
//                         <div key={category.id} className="space-y-4">
//                             {/* Category Title Header */}
//                             <div className="pb-2">
//                                 <div className="flex items-center gap-2">
//                                     <h2 className="text-2xl font-bold tracking-tight">
//                                         {category.title}
//                                     </h2>
//                                 </div>
//                                 <p className="text-sm text-muted-foreground mt-1">
//                                     {category.description}
//                                 </p>
//                             </div>

//                             {/* Courses Grid inside Category */}
//                             <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
//                                 {category.courses.map((course: any) => (
//                                     <Card key={course.id} className="overflow-hidden flex flex-col justify-between hover:shadow-md transition-shadow py-0">
//                                         <div>
//                                             {/* Thumbnail Image */}
//                                             <div className="aspect-video w-full relative bg-muted overflow-hidden">
//                                                 <img 
//                                                     src={course.thumbnail_url} 
//                                                     alt={course.title}
//                                                     className="object-cover w-full h-full hover:scale-105 transition-transform duration-300"
//                                                 />
//                                             </div>

//                                             <CardHeader className="p-4 space-y-2">
//                                                 {/* Meta Badges */}
//                                                 <div className="flex items-center gap-2 flex-wrap">
//                                                     <Badge className={getDifficultyColor(course.difficulty_level)}>
//                                                         {course.difficulty_level}
//                                                     </Badge>
//                                                     <Badge className="uppercase text-[10px]">
//                                                         {course.language}
//                                                     </Badge>
//                                                 </div>

//                                                 <CardTitle className="line-clamp-1 text-lg">
//                                                     {course.title}
//                                                 </CardTitle>

//                                                 <CardDescription className="line-clamp-2 text-sm">
//                                                     {course.description}
//                                                 </CardDescription>
//                                             </CardHeader>
//                                         </div>

//                                         {/* Footer info blocks */}
//                                         <CardFooter className="p-4 pt-0 border-t flex items-center justify-between text-xs text-muted-foreground mt-auto bg-slate-50/50 dark:bg-slate-900/20 h-12">
//                                             <div className="flex items-center gap-1">
//                                                 <Clock className="h-3 w-3" />
//                                                 <span>{formatDuration(course.duration_seconds)}</span>
//                                             </div>
//                                             <Button
//                                             onClick={() => router.visit(`/courses/${course.id}`)} 
//                                             className="flex items-center gap-1 text-primary font-medium cursor-pointer hover:underline">
//                                                 <BookOpen className="h-3 w-3" />
//                                                 <span>View Course</span>
//                                             </Button>
//                                         </CardFooter>
//                                     </Card>
//                                 ))}
//                             </div>
//                         </div>
//                     );
//                 })}
//             </div>

//       </>
//   );
// }

// CategoryIndex.layout = {
//     breadcrumbs: [
//         {
//             title: 'Home',
//             href: home(),
//         },
//         {
//             title: 'Categories',
//             href: 'categories.index',
//         }
//     ],
    
// };
