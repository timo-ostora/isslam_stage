
export interface CourseCard {
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

export interface Category {
    id: number;
    title: string;
    slug: string;
}

export interface ModuleItem {
    id: number;
    position: number;
    itemable_type: "App\\Models\\Lesson" | 'App\\Models\\assessment' | 'unknown';
    itemable: Lesson | Assessment  ;
}
export interface Lesson {
    id : number,
    title : string,
    description : string,
    type : string,
    content_url : string,
    content_text : string,
    duration_seconds : number
}
export interface Assessment {
    id : number,
    title : string,
    description : string,
    type : string,
    duration_seconds : number,
    passing_score : number,
    max_attempts : number,
}

export interface CourseModule {
    id: number;
    title: string;
    description: string | null;
    module_items: ModuleItem[];
}

export interface Course {
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

export interface Enrollment {
    status: 'active' | 'completed' | 'cancelled';
    progress_percentage: number;
}