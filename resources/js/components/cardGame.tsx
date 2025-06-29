import {
  Card,
  CardAction,
  CardContent,
  CardDescription,
  CardHeader
} from "@/components/ui/card"
import { WalletCards } from "lucide-react"
import { ArrowRight } from 'lucide-react';
import { router } from '@inertiajs/react'

type ColorTheme = 'blue' | 'green' | 'red' | 'purple' | 'orange' | 'gray';

const colorClasses = {
  blue: {
    border: 'border-blue-200 hover:border-blue-300',
    text: 'hover:text-blue-600',
    icon: 'bg-blue-100 text-blue-700',
    badge: 'bg-blue-100 text-blue-800 border-blue-200'
  },
  green: {
    border: 'border-green-200 hover:border-green-300',
    text: 'hover:text-green-600',
    icon: 'bg-green-100 text-green-700',
    badge: 'bg-green-100 text-green-800 border-green-200'
  },
  red: {
    border: 'border-red-200 hover:border-red-300',
    text: 'hover:text-red-600',
    icon: 'bg-red-100 text-red-700',
    badge: 'bg-red-100 text-red-800 border-red-200'
  },
  purple: {
    border: 'border-purple-200 hover:border-purple-300',
    text: 'hover:text-purple-600',
    icon: 'bg-purple-100 text-purple-700',
    badge: 'bg-purple-100 text-purple-800 border-purple-200'
  },
  orange: {
    border: 'border-orange-200 hover:border-orange-300',
    text: 'hover:text-orange-600',
    icon: 'bg-orange-100 text-orange-700',
    badge: 'bg-orange-100 text-orange-800 border-orange-200'
  },
  gray: {
    border: 'border-gray-200 hover:border-gray-300',
    text: 'hover:text-gray-600',
    icon: 'bg-gray-100 text-gray-700',
    badge: 'bg-gray-100 text-gray-800 border-gray-200'
  }
};

export const CardGame = ({
  titulo, 
  descrip, 
  modo, 
  ruta,
  colorTheme = 'blue'
}: {
  titulo: string;
  descrip: string;
  modo: string;
  ruta: string;
  colorTheme?: ColorTheme;
}) => {
  const handleClick = () => {
    router.get(route(ruta))
  }

  const colors = colorClasses[colorTheme];

  return (
    <Card className={`group w-full max-w-sm rounded-lg bg-card 
    text-card-foreground shadow-sm h-full transition-all 
    duration-300 hover:shadow-lg border overflow-hidden 
    relative ${colors.border} ${colors.text}`}>
      <CardHeader>
        <WalletCards className={`w-22 h-22 
        p-3 rounded-lg transition-transform duration-300 
        group-hover:scale-110 ${colors.icon}`} />
        <h3 className="text-2xl font-semibold 
        leading-none tracking-tight mt-4 transition-colors 
        duration-300">{titulo}</h3>
        <CardDescription className="text-sm text-muted-foreground">
          {descrip}
        </CardDescription>
        <CardAction>
          <div className={`inline-flex items-center 
          rounded-full border px-2.5 py-0.5 
          text-xs transition-colors focus:outline-none 
          focus:ring-2 focus:ring-ring 
          focus:ring-offset-2 font-medium 
          ${colors.badge}`}>{modo}</div>
        </CardAction>
      </CardHeader>
      <CardContent>
        <button 
        onClick={handleClick}
        className="inline-flex items-center gap-2 
        whitespace-nowrap rounded-md text-sm font-medium 
        ring-offset-background transition-colors focus-visible:outline-none 
        focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 
        disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none 
        [&_svg]:size-4 [&_svg]:shrink-0 hover:bg-accent hover:text-accent-foreground h-10 px-4 
        py-2 w-full justify-between group-hover:bg-background/80">
            Iniciar
            <ArrowRight />
        </button>
      </CardContent>
    </Card>
  )
}