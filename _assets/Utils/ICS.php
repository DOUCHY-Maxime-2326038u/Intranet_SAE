<?php

class ICS
{

    // Fonction pour lire et parser un fichier ICS
    public static function parseICS($filePath): array
    {
        $events = [];
        $icsFile = file_get_contents($filePath);

        preg_match_all('/BEGIN:VEVENT(.*?)END:VEVENT/s', $icsFile, $matches);
        foreach ($matches[1] as $eventData) {
            $event = [];
            if (preg_match('/SUMMARY:(.*?)\n/', $eventData, $summaryMatch)) {
                $event['summary'] = trim($summaryMatch[1]);
            }
            if (preg_match('/DTSTART:(.*?)\n/', $eventData, $startMatch)) {
                $dateTime = Datetime::createFromFormat('Ymd\THis\Z', trim($startMatch[1]), new DateTimeZone('UTC'));
                $event['start'] = $dateTime->format('Y-m-d H:i:s');

            }
            if (preg_match('/DTEND:(.*?)\n/', $eventData, $endMatch)) {
                $dateTime = Datetime::createFromFormat('Ymd\THis\Z', trim($endMatch[1]), new DateTimeZone('UTC'));
                $event['end'] = $dateTime->format('Y-m-d H:i:s');
            }
            if (preg_match('/DESCRIPTION:(.*?)\n/', $eventData, $descMatch)) {
                $event['description'] = trim($descMatch[1]);
            }
            if (preg_match('/LOCATION:(.*?)\n/', $eventData, $locationMatch)) {
                $event['location'] = trim($locationMatch[1]);
            }
            $events[] = $event;
        }
        return $events;
    }

    public static function extractGroup(array $events): array
    {
        $assignments = [];
        $regex = '/GA1-([12])|GA2-([12])|GAB-([12])|GA1|GA2|GB|G[1-4]([AB])?|Groupe\s*(1|2|3|4|A1|A2|B)/';

        foreach ($events as $event) {
            $groups = [];
            $subgroup = '';

            // Recherche des groupes dans summary ou description
            if (preg_match_all($regex, $event['summary'] ?? '', $matches) ||
                preg_match_all($regex, $event['description'] ?? '', $matches)) {

                foreach ($matches[0] as $match) {
                    $group = '';

                    // Conversion des formats complexes en leur équivalent simplifié
                    if (preg_match('/GA1-([12])/', $match, $subMatches)) {
                        $group = 'A1';
                        $subgroup = ($subMatches[1] === '1') ? 'A' : 'B';
                    } elseif (preg_match('/GA2-([12])/', $match, $subMatches)) {
                        $group = 'A2';
                        $subgroup = ($subMatches[1] === '1') ? 'A' : 'B';
                    } elseif (preg_match('/GAB-([12])/', $match, $subMatches)) {
                        $group = 'B1';
                        $subgroup = ($subMatches[1] === '1') ? 'A' : 'B';
                    } elseif ($match === 'GA1') {
                        $group = 'A1';
                    } elseif ($match === 'GA2') {
                        $group = 'A2';
                    } elseif ($match === 'GB') {
                        $group = 'B1';
                    } elseif (preg_match('/G([1-4])([AB])?/', $match, $subMatches)) {
                        $group = $subMatches[1];
                        $subgroup = $subMatches[2] ?? ''; // A ou B si présent
                    } elseif (preg_match('/Groupe (1|2|3|4|A1|A2|B)/', $match, $groupMatch)) {
                        $group = $groupMatch[1];
                        if ($group === 'B') {
                            $group = 'B1';
                        }
                    }

                    if ($group !== '') {
                        $groups[] = $group;
                    }
                }
            }

            // Création d'un seul `assignment` par événement
            $assignments[] = [
                'course_name' => $event['summary'] ?? '',
                'location' => $event['location'] ?? '',
                'start' => $event['start'] ?? '',
                'end' => $event['end'] ?? '',
                'groups' => array_unique($groups), // Liste des groupes associés
                'subgroup' => $subgroup,
            ];
        }

        return $assignments;
    }

}