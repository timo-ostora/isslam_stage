// Shared types for the course player (LearningController@show payload).
// Kept separate from the page component since this shape is reused by
// LessonProgressController/AttemptController-facing pages once they exist.

export interface LessonContent {
    id: number;
    title: string;
    description: string | null;
    type: 'video' | 'article' | 'pdf' | 'link';
    content_url: string | null;
    content_text: string | null;
    duration_seconds: number;
}

export interface AssessmentContent {
    id: number;
    title: string;
    description: string | null;
    type: 'quiz' | 'exam' | 'assignment';
    duration_seconds: number | null;
    passing_score: number;
    max_attempts: number | null;
}

export interface LearningModuleItem {
    id: number;
    module_id: number;
    position: number;
    itemable_type: string;
    // Only present once ModuleItem::$appends includes 'content_type' —
    // falls back to parsing itemable_type if absent, see contentTypeOf().
    content_type?: 'lesson' | 'assessment' | 'unknown';
    itemable: LessonContent | AssessmentContent;
}

export interface LearningModule {
    id: number;
    title: string;
    description: string | null;
    position: number;
    module_items: LearningModuleItem[];
}

export interface LearningCourse {
    id: number;
    title: string;
    slug: string;
    category?: { title: string; slug: string } | null;
    creator?: { name: string } | null;
    modules: LearningModule[];
}

export interface LearningEnrollment {
    status: 'active' | 'completed' | 'cancelled';
    progress_percentage: number;
}

export interface LearningPayload {
    course: LearningCourse;
    enrollment: LearningEnrollment;
    currentItem: LearningModuleItem;
    previousItem: LearningModuleItem | null;
    nextItem: LearningModuleItem | null;
    statistics: { totalModules: number; totalItems: number };
}

export function contentTypeOf(item: LearningModuleItem): 'lesson' | 'assessment' | 'unknown' {
    if (item.content_type) return item.content_type;
    if (item.itemable_type?.endsWith('Lesson')) return 'lesson';
    if (item.itemable_type?.endsWith('Assessment')) return 'assessment';
    return 'unknown';
}

export function isLesson(item: LearningModuleItem): item is LearningModuleItem & { itemable: LessonContent } {
    return contentTypeOf(item) === 'lesson';
}

export function isAssessment(item: LearningModuleItem): item is LearningModuleItem & { itemable: AssessmentContent } {
    return contentTypeOf(item) === 'assessment';
}