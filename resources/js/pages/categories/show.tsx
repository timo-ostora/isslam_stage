// import { Head, router } from '@inertiajs/react';
// import { home } from '@/routes';
// import { Button } from '@/components/ui/button';
// import { Play } from 'lucide-react';
// import { usePage } from '@inertiajs/react';

// export default function CategoryShow() {
//     let defaultUser = {
//         name: 'User',
//         enrolledCourses: [],
//         completedLessons: [],
//         statistics: {
//             studyHoursThisWeek: 0,
//             completedCourses: 0,
//             averageExamScore: null,
//             weeklyProgress: [],
//         },
//         certificates: [],
//     };

//     let user = usePage().props.auth.user || defaultUser;

//     return (
//         <main>
//           <Head title="Home" />

//             <div 
//                 className=" xl:rounded-md xl:my-4 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 p-8 text-white relative overflow-hidden shadow-xl shadow-blue-500/10"
//             >
//                 <div className="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
//                     <div className="space-y-2 text-left">
//                         <h2 className="text-2xl md:text-3xl font-bold font-heading">
//                         Welcome back, {user?.name}! 👋
//                         </h2>
//                         <p className="text-slate-100/80 text-sm max-w-md">
//                         You are completing lessons quickly. Keep it up to qualify for your React Architecture certifications!
//                         </p>
//                     </div>
//                     <Button 
//                         variant="secondary"
//                         // className="self-start md:self-auto bg-white hover:bg-slate-50 text-blue-600 border-none font-semibold focus:ring-white"
//                         className="cursor-pointer"
//                         onClick={() => router.visit(home())}
//                     >
//                         Resume Learning
//                         <Play className="h-4 w-4 fill-current" />
//                     </Button>
//                 </div>
//             </div>

//         </main>
//     );
// }

// CategoryShow.layout = {
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
