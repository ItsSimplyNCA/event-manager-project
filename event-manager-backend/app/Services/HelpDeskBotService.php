<?php

namespace App\Services;

use Illuminate\Support\Str;

class HelpDeskBotService {

    public function respond(string $message): array {
        
        $text = Str::lower($message);

        if (Str::contains($text, ['ember', 'ügyintéző', 'agent', 'human', 'operátor'])) {
            return [
                'reply' => 'Rendben, továbbítom a beszélgetést egy emberi ügyintézőnek.',
                'handoff' => true,
            ];
        }

        if (Str::contains($text, ['jelszó', 'password', 'reset'])) {
            return [
                'reply' => 'Jelszó-visszaállításhoz használd a bejelentkezési képernyőn az "Elfelejtett jelszó" lehetőséget.',
                'handoff' => false,
            ];
        }

        if (Str::contains($text, ['esemény', 'event', 'létrehoz', 'create'])) {
            return [
                'reply' => 'Új eseményt az eseményoldalon a cím, időpont és opcionális leírás megadásával hozhatsz létre.',
                'handoff' => false,
            ];
        }

        if (Str::contains($text, ['töröl', 'delete'])) {
            return [
                'reply' => 'Egy eseményt a listaelemnél található törlés gombbal tudsz törölni.',
                'handoff' => false,
            ];
        }

        return [
            'reply' => 'Megpróbálok segíteni. Írd le pontosabban a problémát, vagy írd azt, hogy "emberi ügyintézőt kérek".',
            'handoff' => false,
        ];
    }
}