import { CardGame } from '@/components/cardGame';
import { ChartColumn, History } from 'lucide-react';

export default function ManagerGames() {
  return (
    <div className="container mx-auto min-h-screen px-4 py-6">
      <header className="mb-8 flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
        <div>
          <h1 className="text-3xl font-bold tracking-tight">
            Game Tracker
          </h1>
          <p className="text-muted-foreground mt-1">
            Lleva el control de tus juegos y mantén un registro de las ganancias
          </p>
        </div>
        <div className="flex items-center gap-2" >
          <a
            href="/history"
            className="ring-offset-background focus-visible:ring-ring border-input bg-background hover:bg-accent hover:text-accent-foreground inline-flex h-9 items-center justify-center gap-2 rounded-md border px-3 text-sm font-medium whitespace-nowrap transition-colors focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0"
          >
            <History/>Historial
          </a>
          <a
            href="/history"
            className="ring-offset-background focus-visible:ring-ring border-input bg-background hover:bg-accent hover:text-accent-foreground inline-flex h-9 items-center justify-center gap-2 rounded-md border px-3 text-sm font-medium whitespace-nowrap transition-colors focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0"
          >
            <ChartColumn/>Estadísticas
          </a>
        </div>
      </header>
      <div className="grid gap-6 p-5 md:grid-cols-2 lg:grid-cols-3">
        <CardGame 
          titulo="Golpeado" 
          descrip="Registra las ganancias y pérdidas en juegos de cartas como Poker, Truco, etc." 
          modo="popular"
          ruta='golpeao'/>
        <CardGame 
          titulo="Bingo" 
          descrip="Lleva el control de tus cartones, premios y ganancias en el bingo." 
          modo="nuevo"
          ruta='#'
          colorTheme="orange"/>
      </div>
    </div>
  );
}
