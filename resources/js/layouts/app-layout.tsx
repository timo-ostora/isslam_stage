import AppLayoutTemplate from '@/layouts/app/app-header-layout';
import type { BreadcrumbItem } from '@/types';
import { usePage } from '@inertiajs/react';

export default function AppLayout({
    breadcrumbs = [],
    children,
}: {
    breadcrumbs?: BreadcrumbItem[];
    children: React.ReactNode;
}) {
    const { props }:any = usePage<{
        breadcrumbs ?: BreadcrumbItem[];
    }>
    
    const resolvedBreadcrumbs = breadcrumbs ?? props.breadcrumbs ?? [];

    return (
        <AppLayoutTemplate breadcrumbs={resolvedBreadcrumbs}>
            {children}
        </AppLayoutTemplate>
    );
}
