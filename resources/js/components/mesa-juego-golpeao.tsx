import { useState } from 'react';

interface User {
  id: number;
  name: string;
  email: string;
}

interface Player {
  id: number;
  nombre: string;
  apellido: string;
  nombre_completo: string;
  email: string | null;
  user: User | null;
}

interface JugadorEnMesa extends Player {
  color: string;
  fondo: string;
  inicial: string;
  seleccionado: boolean;
}

interface HomeGolpeaoProps {
  players?: Player[];
}

export default function Mesa_juego({
    players=[]
}:HomeGolpeaoProps){
    const [jugadoresEnMesa, setJugadoresEnMesa] = useState<JugadorEnMesa[]>([]);
    const [emailBusqueda, setEmailBusqueda] = useState<string>('');
    const [mostrarListaJugadores, setMostrarListaJugadores] = useState<boolean>(false);
    const [jugadoresFiltrados, setJugadoresFiltrados] = useState<Player[]>(players);

    const filtrarPlayers = (email:string):void => {
        setEmailBusqueda(email);
        if (email.trim() === ''){
        setJugadoresFiltrados(players);
        }else{
        const filtrados = players.filter(player => 
            player.email?.toLowerCase().includes(email.toLowerCase())
        );
        setJugadoresFiltrados(filtrados);
        }
    };

    const addPlayerForEmail = ():void=>{
        const playerFind = players.find(player => player.email?.toLowerCase() === emailBusqueda.toLowerCase());

        if (playerFind && !jugadoresEnMesa.find(j=>j.id === playerFind.id)){
            const colores: string[] = ['bg-blue-500', 'bg-green-500', 'bg-purple-500', 'bg-orange-500', 'bg-pink-500', 'bg-indigo-500'];
            const fondos: string[] = ['bg-blue-50 border-blue-200', 'bg-green-50 border-green-200', 'bg-purple-50 border-purple-200', 'bg-orange-50 border-orange-200', 'bg-pink-50 border-pink-200', 'bg-indigo-50 border-indigo-200'];

            const jugadorConEstilo: JugadorEnMesa = {
                ...playerFind,
                color: colores[jugadoresEnMesa.length % colores.length],
                fondo: fondos[jugadoresEnMesa.length % fondos.length],
                inicial: playerFind.nombre.charAt(0).toUpperCase(),
                seleccionado: false
            };

            setJugadoresEnMesa([...jugadoresEnMesa, jugadorConEstilo]);
            setEmailBusqueda('');
            setJugadoresFiltrados(players);

        }
    }



    return (
        <div></div>
    );
}