
export function AppFooter() {
    return (
        <footer className="mt-8 bg-slate-50 dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800">
            <div className="max-w-screen-xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                <p className="text-center text-sm text-slate-500 dark:text-slate-400">
                    &copy; {new Date().getFullYear()} iSSLAM LMS. All rights reserved.
                </p>
            </div>
        </footer>
    );
}