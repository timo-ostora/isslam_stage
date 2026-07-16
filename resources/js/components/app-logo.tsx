import AppLogoIcon from '@/components/app-logo-icon';

export default function AppLogo() {
    return (
        <div className='flex items-center gap-2'>
            {/* <div className="flex aspect-square size-8 items-center justify-center rounded-md bg-sidebar-primary text-sidebar-primary-foreground"> */}
            <div className='flex max-w-12'>
                <AppLogoIcon className="size-5 fill-current text-white dark:text-black" />
            </div>
            <h4 className=' font-bold color-green'>
                Online Lerning
            </h4>
        </div>
    );
}
