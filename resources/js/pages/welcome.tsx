import { type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';
import { CircleCheckBig, ArrowRight  } from 'lucide-react';
import { Button } from "@/components/ui/button"


export default function Welcome() {
    const { auth } = usePage<SharedData>().props;

    return (
        <>
            <Head title="Welcome">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
            </Head>
            <div className="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#0a0a0a]">
                <header className="mb-6 w-full max-w-[335px] text-sm not-has-[nav]:hidden lg:max-w-4xl">
                    <nav className="flex items-center justify-end gap-4">
                        {auth.user ? (
                            <Link
                                href={route('managerGames')}
                                className="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                            >
                                home
                            </Link>
                        ) : (
                            <>
                                <Link
                                    href={route('login')}
                                    className="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                                >
                                    Log in
                                </Link>
                                <Link
                                    href={route('register')}
                                    className="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                                >
                                    Register
                                </Link>
                            </>
                        )}
                    </nav>
                </header>
                <section className="container mx-auto px-4 py-20 text-center">
                    <div className="mx-auto max-w-4xl" >
                        <h1 className="animate-in fade-in-50 mb-6 bg-gradient-to-r 
                            from-slate-900 via-blue-900 to-slate-900 bg-clip-text 
                            text-4xl font-bold text-transparent duration-700 md:text-6xl">
                            Lleva el control de tus{' '}
                            <span className="bg-gradient-to-r from-blue-600 to-purple-600 
                            bg-clip-text text-transparent" > partidas de juego </span>
                        </h1>
                        <p className="animate-in fade-in-50 mx-auto mb-8 max-w-2xl text-xl text-slate-600 duration-900" >
                            Registra ganancias, gestiona jugadores y mantén estadísticas detalladas de todas tus partidas de cartas, bingo y otros
                            juegos de mesa.
                        </p>
                        <div className="animate-in fade-in-50 
                        flex flex-col items-center justify-center 
                        gap-4 duration-1000 sm:flex-row">
                            <Button className='px-6'>
                                <Link href={route('managerGames')} className="flex items-center">
                                    Comenzar Gratis <ArrowRight className='ml-2 h-5 w-5'/>
                                </Link>                            
                            </Button>
                            <Button variant={'outline'}>
                                <Link href='#' className='flex items-center'>Ver Demo</Link>
                            </Button>
                        </div>
                        <div className="animate-in fade-in-50 mt-12 flex items-center 
                        justify-center space-x-8 text-sm text-slate-500 duration-1200">
                            <div className="flex items-center" >
                                <CircleCheckBig size={16} className='text-green-400'/>
                                Gratis para siempre
                            </div>
                            <div className="flex items-center">
                                <CircleCheckBig size={16} className='text-green-400'/>
                                Sin límite de partidas
                            </div>
                            <div className="flex items-center">
                                <CircleCheckBig size={16} className='text-green-400'/>
                                Multiplataforma
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </>
    );
}
