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

    

    return (
        <div></div>
    );
}