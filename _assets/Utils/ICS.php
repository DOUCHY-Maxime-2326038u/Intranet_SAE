<?php

class ICS
{
//    public static function parseICS($fileContent): array
//    {
//        $events = [];
//        $lines = explode("\n", $fileContent);
//        $event = [];
//        foreach ($lines as $line) {
//            $line = trim($line);
//            if ($line === "BEGIN:VEVENT") {
//                $event = [];
//            } elseif ($line === "END:VEVENT") {
//                $events[] = $event;
//            } else {
//                [$key, $value] = array_pad(explode(":", $line, 2), 2, null);
//                $key = trim($key);
//                $value = trim($value);
//                if (isset($event[$key])) {
//                    $event[$key] .= " $value"; // Support pour les lignes longues
//                } else {
//                    $event[$key] = $value;
//                }
//            }
//        }
//        return $events;
//    }
//
//    public static function extractGroupsByYear($summary, $description, $year): array
//    {
//        $groups = [];
//
//        switch ($year) {
//            case 1: // 1ère année
//                preg_match_all('/G([1234])([A-B])?/i', $summary . ' ' . $description, $matches);
//                $groups = $matches[0]; // Exemples : G1, G1A, G4B
//                break;
//
//            case 2: // 2ème année
//            case 3: // 3ème année
//                preg_match_all('/G(A1|A2|B)([A-B])?/i', $summary . ' ' . $description, $matches);
//                $groups = $matches[0]; // Exemples : GA1, GB, GA2A
//                break;
//        }
//
//        return $groups;
//    }

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
        $regex = '/\b(?:GA1-([12])|GA2-([12])|GAB-([12])|GA1|GA2|GB|G[1-4]([AB])?|Groupe (1|2|3|4|A1|A2|B))\b/';

        foreach ($events as $event) {
            $groups = [];
            $subgroup = '';

            // Recherche des groupes dans summary ou description
            if (preg_match_all($regex, $event['summary'] ?? '', $matches) ||
                preg_match_all($regex, $event['description'] ?? '', $matches)) {

                foreach ($matches[0] as $match) {
                    $group = '';
                    $subgroup = '';

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
                    } elseif ($match == 'GA1') {
                        $group = 'A1';
                    } elseif ($match == 'GA2') {
                        $group = 'A2';
                    } elseif ($match == 'GB') {
                        $group = 'B1';
                    } elseif (preg_match('/G([1-4])([AB])?/', $match, $subMatches)) {
                        $group = $subMatches[1];
                        $subgroup = $subMatches[2] ?? ''; // A ou B si présent
                    } elseif (preg_match('/Groupe (1|2|3|4|A1|A2|B)/', $match, $groupMatch)) {
                        if ($groupMatch[1][0] === 'B') {
                            $groupMatch[1][0] = 'B1';
                        }
                        $group = $groupMatch[1]; // Récupérer uniquement le numéro ou le sous-groupe
                    }

                    if ($group !== '') {
                        $groups[] = [
                            'group' => $group,
                            'subgroup' => $subgroup,
                        ];
                    }
                }
            }

            foreach ($groups as $groupInfo) {
                $assignments[] = [
                    'course_name' => $event['summary'] ?? '',
                    'location' => $event['location'] ?? '',
                    'start' => $event['start'] ?? '',
                    'end' => $event['end'] ?? '',
                    'group' => $groupInfo['group'],
                    'subgroup' => $groupInfo['subgroup'],
                ];
            }
        }

        return $assignments;
    }

// Fonction pour assigner les cours aux groupes
//    public static function assignCoursesToGroups($events): array
//    {
//        $assignments = [];
//        foreach ($events as $event) {
//            $groups = [];
//            // Analyse du champ DESCRIPTION ou SUMMARY pour identifier les groupes
//            if (isset($event['description'])) {
//                if (preg_match_all('/Groupe (.*?)\b/', $event['description'], $groupMatches)) {
//                    $groups = $groupMatches[1];
//                } elseif (preg_match_all('/(G[1-4][AB]?|GA[1-2][AB]?|GB)/', $event['description'], $groupMatches)) {
//                    $groups = $groupMatches[0];
//                }
//            }
//
//            // Si aucun groupe trouvé, essayez avec SUMMARY
//            if (empty($groups) && isset($event['summary'])) {
//                if (preg_match_all('/G[1-4][AB]?|GA[1-2][AB]?|GB/', $event['summary'], $groupMatches)) {
//                    $groups = $groupMatches[0];
//                }
//            }
//
//            // Enregistrement des affectations
//            foreach ($groups as $group) {
//                $assignments[] = [
//                    'course' => $event['summary'],
//                    'start' => $event['start'],
//                    'end' => $event['end'],
//                    'location' => $event['location'] ?? null,
//                    'group' => $group,
//                ];
//            }
//        }
//        return $assignments;
//    }



}