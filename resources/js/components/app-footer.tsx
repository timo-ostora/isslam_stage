import AppLogo from "./app-logo";

const platformLinks = [
    { label: 'Home', href: '/' },
    { label: 'Browse Courses', href: '/courses' },
    { label: 'Popular Courses', href: '/#popular-courses' },
    { label: 'Instructors', href: '/#instructors' },
];

const companyLinks = [
    { label: 'About Us', href: '/#about' },
    { label: 'Contact', href: '/contact' },
    { label: 'Help Center', href: '/help' },
];

const legalLinks = [
    { label: 'Privacy Policy', href: '/privacy-policy' },
    { label: 'Terms of Service', href: '/terms' },
];

export function AppFooter() {
    const currentYear = new Date().getFullYear();

    return (
        <footer className="mt-16 border-t border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-950">
            <div className="mx-auto max-w-screen-xl px-4 py-12 sm:px-6 lg:px-8">
                <div className="grid gap-10 sm:grid-cols-2 lg:grid-cols-5">
                    {/* Brand */}
                    <div className="sm:col-span-2">
                        <a
                            href="/"
                            className="inline-flex items-center gap-3 text-slate-900 dark:text-white"
                        >
                            <AppLogo />
                        </a>

                        <p className="mt-5 max-w-md text-sm leading-7 text-slate-600 dark:text-slate-400">
                            Learn practical skills through structured courses
                            created by experienced instructors. Study at your
                            own pace and keep moving toward your goals.
                        </p>

                        <div className="mt-6 space-y-3 text-sm text-slate-600 dark:text-slate-400">
                            <a
                                href="mailto:contact@example.com"
                                className="block transition-colors hover:text-primary"
                            >
                                contact@example.com
                            </a>

                            <p>Rabat, Morocco</p>
                        </div>
                    </div>

                    {/* Platform */}
                    <FooterLinks
                        title="Platform"
                        links={platformLinks}
                    />

                    {/* Company */}
                    <FooterLinks
                        title="Company"
                        links={companyLinks}
                    />

                    {/* Legal and CTA */}
                    <div>
                        <FooterLinks
                            title="Legal"
                            links={legalLinks}
                        />

                        <div className="mt-8 rounded-2xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
                            <p className="font-semibold text-slate-900 dark:text-white">
                                Ready to learn?
                            </p>

                            <p className="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-400">
                                Explore available courses and start building
                                your skills today.
                            </p>

                            <a
                                href="/courses"
                                className="mt-4 inline-flex items-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground transition-opacity hover:opacity-90"
                            >
                                Explore courses
                                <span className="ml-2" aria-hidden="true">
                                    →
                                </span>
                            </a>
                        </div>
                    </div>
                </div>

                <div className="mt-12 flex flex-col gap-4 border-t border-slate-200 pt-6 text-sm text-slate-500 dark:border-slate-800 dark:text-slate-400 sm:flex-row sm:items-center sm:justify-between">
                    <p>
                        &copy; {currentYear} Online Learning. All rights
                        reserved.
                    </p>

                    <p>
                        Learn at your own pace. Grow with confidence.
                    </p>
                </div>
            </div>
        </footer>
    );
}

interface FooterLink {
    label: string;
    href: string;
}

interface FooterLinksProps {
    title: string;
    links: FooterLink[];
}

function FooterLinks({ title, links }: FooterLinksProps) {
    return (
        <div>
            <h2 className="font-semibold text-slate-900 dark:text-white">
                {title}
            </h2>

            <ul className="mt-5 space-y-3">
                {links.map((link) => (
                    <li key={link.label}>
                        <a
                            href={link.href}
                            className="text-sm text-slate-600 transition-colors hover:text-primary dark:text-slate-400"
                        >
                            {link.label}
                        </a>
                    </li>
                ))}
            </ul>
        </div>
    );
}