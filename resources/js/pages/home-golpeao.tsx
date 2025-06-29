import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Link } from '@inertiajs/react';
import { ArrowLeft, Music, Trophy, Crown, Users, CirclePlus, Flame, BarChart3 } from 'lucide-react';
//import { useState } from 'react';

export default function HomeGolpeao() {

  // const [players, setPlayers] = useState<string[]>([])


  return (
    <div className="mx-auto flex min-h-screen flex-col px-4 py-4">
      <div className="mb-4 flex items-center justify-between">
        <div className="flex items-center gap-2">
          <Link href={route('managerGames')}>
            <Button variant="ghost" size="icon">
              <ArrowLeft />
            </Button>
          </Link>
          <h1 className="text-2xl font-bold">Golpeado</h1>
        </div>
        <div className="flex items-center gap-2">
          <Popover>
            <PopoverTrigger asChild>
              <Button variant="outline" size="default">
                <Music />
              </Button>
            </PopoverTrigger>
            <PopoverContent></PopoverContent>
          </Popover>
        </div>
      </div>
      <div className='grid grid-cols-1 md:grid-cols-3 gap-4 mb-6'>
        <Card className="bg-yellow-50 border-yellow-200">
          <CardHeader className="pb-2">
            <div className="flex items-start justify-between">
              <div className="flex items-center gap-2">
                <Trophy className="h-5 w-5 text-yellow-600" />
              </div>
              <Badge className="bg-yellow-200 text-yellow-800 hover:bg-yellow-200">
                100%
              </Badge>
            </div>
            <CardTitle className="text-lg mt-2">Juan</CardTitle>
            <p className="text-sm text-yellow-700">3 victorias de 3</p>
          </CardHeader>
        </Card>
        <Card className="bg-red-50 border-red-200">
          <CardHeader className="pb-2">
            <div className="flex items-start justify-between">
              <div className="flex items-center gap-2">
                <Flame className="h-5 w-5 text-red-500" />
                <Badge className="bg-red-100 text-red-600">¡EN RACHA!</Badge>
              </div>
              <Badge className="bg-red-200 text-red-800">3x</Badge>
            </div>
            <CardTitle className="text-lg mt-2">Juan</CardTitle>
            <p className="text-sm text-red-600">3 victorias consecutivas</p>
          </CardHeader>
        </Card>
        <Card className="bg-blue-50 border-blue-200">
          <CardHeader className="pb-2">
            <div className="flex items-center gap-2 mb-2">
              <BarChart3 className="h-5 w-5 text-blue-600" />
              <Badge variant="secondary" className="text-xs">3 rondas</Badge>
            </div>
            <CardTitle className="text-2xl font-bold">2</CardTitle>
            <p className="text-sm text-blue-600">Jugadores activos</p>
          </CardHeader>
        </Card>
      </div>
      <div className='grid grid-cols-1 lg:grid-cols-4 gap-6'>
        <div className="flex-2/3 rounded-lg border bg-card text-card-foreground shadow-sm h-full">
          <div className="flex flex-col space-y-1.5 p-6 pb-2">
            <div className="flex justify-between items-center">
              <h3 className="text-2xl font-semibold leading-none tracking-tight">Mesa de Juego</h3>
              <div className="flex items-center gap-2">
                <Button>Nueva Ronda</Button>
              </div>
            </div>
          </div>
          <div className="p-6 flex flex-col items-center justify-center py-4">
            <div className="w-full max-w-4xl mx-auto">
              <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4"></div>
              <div className="text-center py-8">
                <div className="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                  <Crown />
                </div>
                <p className="text-muted-foreground text-lg">No hay jugadores en la mesa</p>
                <p className="text-sm text-muted-foreground mt-1">Añade jugadores para comenzar la partida</p>
              </div>
            </div>
            <div className="flex flex-col gap-2 mt-6 w-full max-w-md">
              <div className="flex space-x-2">
                <Input type='email' placeholder='Correo del jugador'/>
                <Button className='bg-green-700'><CirclePlus/>Añadir</Button>
                <Button variant='outline' ><Users/></Button>
              </div>
            </div>
          </div>
        </div>
        <div className="space-y-4">
          <div className="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div className="p-6 py-6 text-center">
              <p className="text-muted-foreground text-sm">Añade jugadores para comenzar la partida</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
