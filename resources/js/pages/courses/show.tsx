// import { Head, Link, router, useForm, usePage } from '@inertiajs/react';

// interface ModuleItem {
//     id: number;
//     position: number;
//     type: 'lesson' | 'assessment' | 'unknown';
//     title: string | null;
//     duration_seconds: number | null;
// }

// interface CourseModule {
//     id: number;
//     title: string;
//     description: string | null;
//     items: ModuleItem[];
// }

// interface Course {
//     id: number;
//     title: string;
//     slug: string;
//     description: string | null;
//     thumbnail_url: string | null;
//     difficulty_level: 'easy' | 'medium' | 'hard';
//     duration: string;
//     language: string;
//     category?: { title: string; slug: string };
//     creator?: { name: string };
//     modules?: CourseModule[];
// }

// interface Enrollment {
//     status: 'active' | 'completed' | 'cancelled';
//     progress_percentage: number;
// }

// interface PageProps {
//     course: Course;
//     enrollment: Enrollment | null;
//     auth: { user: { id: number; name: string } | null };
// }

// function formatDuration(seconds: number | null): string {
//     if (!seconds) return '';
//     const m = Math.round(seconds / 60);
//     return `${m} min`;
// }

// export default function CourseShow() {
//     const { course, enrollment, auth } = usePage<any>().props;
//     const enrollForm = useForm({});

//     const isEnrolled = enrollment?.status === 'active' || enrollment?.status === 'completed';

//     function handleEnroll() {
//         // enrollForm.post(router.visit('/enrollments/store/'+{course.slug}), {
//         //     preserveScroll: true,
//         // });
//     }

//     return (
//         <>
//             <Head title={course.title} />

//             <div className="mx-auto max-w-4xl px-4 py-10">
//                 {/* Header */}
//                 <div className="overflow-hidden rounded-xl border border-gray-200">
//                     {course.thumbnail_url && (
//                         <img
//                             src={course.thumbnail_url}
//                             alt={course.title}
//                             className="h-64 w-full object-cover"
//                         />
//                     )}

//                     <div className="space-y-4 p-6">
//                         <div className="flex flex-wrap items-center gap-2 text-sm text-gray-500">
//                             {course.category && (
//                                 <Link
//                                     href={`categories/show/${course.category.slug}`}
//                                     className="rounded-full bg-gray-100 px-3 py-1 hover:bg-gray-200"
//                                 >
//                                     {course.category.title}
//                                 </Link>
//                             )}
//                             <span className="rounded-full bg-gray-100 px-3 py-1 capitalize">
//                                 {course.difficulty_level}
//                             </span>
//                             <span className="rounded-full bg-gray-100 px-3 py-1">
//                                 {course.duration}
//                             </span>
//                             <span className="rounded-full bg-gray-100 px-3 py-1 uppercase">
//                                 {course.language}
//                             </span>
//                         </div>

//                         <h1 className="text-3xl font-bold text-gray-900">{course.title}</h1>

//                         {course.creator && (
//                             <p className="text-sm text-gray-500">
//                                 Created by <span className="font-medium">{course.creator.name}</span>
//                             </p>
//                         )}

//                         <p className="text-gray-700">{course.description}</p>

//                         {/* Enrollment CTA */}
//                         <div className="pt-2">
//                             {!auth.user ? (
//                                 <Link
//                                     href={'auth/login'}
//                                     className="inline-block rounded-lg bg-gray-900 px-5 py-2.5 text-white hover:bg-gray-700"
//                                 >
//                                     Log in to enroll
//                                 </Link>
//                             ) : isEnrolled ? (
//                                 <div className="space-y-2">
//                                     <Link
//                                         href={`learning/show/${course.slug}`}
//                                         className="inline-block rounded-lg bg-gray-900 px-5 py-2.5 text-white hover:bg-gray-700"
//                                     >
//                                         {enrollment?.status === 'completed' ? 'Review Course' : 'Continue Learning'}
//                                     </Link>
//                                     {enrollment && enrollment.status === 'active' && (
//                                         <div className="h-2 w-full max-w-xs overflow-hidden rounded-full bg-gray-100">
//                                             <div
//                                                 className="h-full rounded-full bg-gray-900"
//                                                 style={{ width: `${enrollment.progress_percentage}%` }}
//                                             />
//                                         </div>
//                                     )}
//                                 </div>
//                             ) : (
//                                 <button
//                                     onClick={handleEnroll}
//                                     disabled={enrollForm.processing}
//                                     className="rounded-lg bg-gray-900 px-5 py-2.5 text-white hover:bg-gray-700 disabled:opacity-50"
//                                 >
//                                     {enrollForm.processing ? 'Enrolling…' : 'Enroll for free'}
//                                 </button>
//                             )}
//                         </div>
//                     </div>
//                 </div>

//                 {/* Syllabus */}
//                 <div className="mt-10">
//                     <h2 className="mb-4 text-xl font-semibold text-gray-900">Course content</h2>

//                     <div className="space-y-4">
//                         {course.modules?.map((module: any) => (
//                             <div key={module.id} className="rounded-lg border border-gray-200">
//                                 <div className="border-b border-gray-200 bg-gray-50 px-4 py-3">
//                                     <h3 className="font-medium text-gray-900">{module.title}</h3>
//                                     {module.description && (
//                                         <p className="mt-1 text-sm text-gray-500">{module.description}</p>
//                                     )}
//                                 </div>

//                                 {/* <ul className="divide-y divide-gray-100">
//                                     {module.items.map((item:any) => (
//                                         <li
//                                             key={item.id}
//                                             className="flex items-center justify-between px-4 py-3 text-sm"
//                                         >
//                                             <div className="flex items-center gap-3">
//                                                 <span className="text-gray-400">
//                                                     {item.type === 'assessment' ? '📝' : '▶'}
//                                                 </span>
//                                                 <span className={isEnrolled ? 'text-gray-900' : 'text-gray-500'}>
//                                                     {item.title ?? 'Untitled'}
//                                                 </span>
//                                             </div>

//                                             <div className="flex items-center gap-3 text-gray-400">
//                                                 {item.duration_seconds && (
//                                                     <span>{formatDuration(item.duration_seconds)}</span>
//                                                 )}
//                                                 {!isEnrolled && <span>🔒</span>}
//                                             </div>
//                                         </li>
//                                     ))}
//                                 </ul> */}
//                             </div>
//                         ))}
//                     </div>
//                 </div>
//             </div>
//         </>
//     );
// }