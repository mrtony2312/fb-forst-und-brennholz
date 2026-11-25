<?php

namespace App\Domain\Merchant\Support;

/**
 * Normalizes legacy Swiss spelling (ss instead of ß) and leftover French
 * fragments in catalog copy so the German storefront and feed stay consistent
 * with the DE market / language declared in Merchant Center.
 */
class GermanCopyNormalizer
{
    /**
     * @var array<string, string>
     */
    private const PHRASES = [
        'Pays Bas' => 'Niederlande',
        'Disponible en stock' => 'Auf Lager',
        'Retours possible sous 30 jours' => 'Rückgabe innerhalb von 14 Tagen',
        'Lieferung gratuite (5 à 8 jours ouvrables)' => 'Kostenlose Lieferung nach Deutschland in 2 bis 5 Werktagen',
        'Holzpellets Nadelholz de 6 mm certifiés ENplusA1' => 'Holzpellets aus Nadelholz, 6 mm, zertifiziert nach ENplus A1',
        'Restfeuchte max 6.1% (Heizwert élevé)' => 'Restfeuchte max. 6,1 % (hoher Heizwert)',
        'Aschegehalt max 0.24% (faible effort de nettoyage)' => 'Aschegehalt max. 0,24 % (geringer Reinigungsaufwand)',
        'Faibles émissions de CO2 lors de die Verbrennung' => 'Geringe CO2-Emissionen bei der Verbrennung',
        'Point de ramollissement der Asche élevé 1450°C (pas de formation de laitier)' => 'Hoher Ascheerweichungspunkt von 1450 °C (keine Schlackebildung)',
        'Régulation Maestro, connecté' => 'Maestro-Regelung, vernetzt',
        'Kamineinsatz hydro à Pellets' => 'Wasserführender Pelletkamineinsatz',
        'Kamineinsatz à Pellets' => 'Pelletkamineinsatz',
        'Feinanteil: Inférieur oder égal à 0,5%' => 'Feinanteil: kleiner oder gleich 0,5 %',
        'Herkunft: Union Européenne' => 'Herkunft: Europäische Union',
        'Ne pas acheter à l’avance s’ils ne sind pas utilisés in l’année.' => 'Nicht im Voraus kaufen, wenn sie nicht innerhalb eines Jahres verwendet werden.',
        'Teneur en eau' => 'Wassergehalt',
        'Palette mit 36 Säcke à 15KG SOIT 990KG' => 'Palette mit 36 Säcken à 15 kg, insgesamt 990 kg',
        'Palette mit 66 Säcke à 15KG SOIT 990KG' => 'Palette mit 66 Säcken à 15 kg, insgesamt 990 kg',
        'Palette = 40 Netze à 25Kg soit' => 'Palette = 40 Netze à 25 kg, insgesamt',
        'Holzpellets de Naturkraft – Palette mit 66 Säcke (Soit 990Kg)' => 'Holzpellets Naturkraft – Palette mit 66 Säcken (insgesamt 990 kg)',
        'Espèces Holz' => 'Holzart',
        'Klasse de propriétés selon die Norm' => 'Eigenschaftsklasse gemäß der Norm',
        'Geeignet für Öfen, Kamineinsätze und Heizkessel à Holzpellets' => 'Geeignet für Öfen, Kamineinsätze und Heizkessel mit Holzpellets',
        'Länge: 3,15 à 40 mm' => 'Länge: 3,15 bis 40 mm',
        'Länge : 26 à 29 cm' => 'Länge: 26 bis 29 cm',
        'Länge: 26 à 29 cm' => 'Länge: 26 bis 29 cm',
        'Trockenholz 2 Jahre Einschlag, gelagert überdacht, à fort Heizwert' => 'Trockenholz, 2 Jahre nach Einschlag überdacht gelagert, mit hohem Heizwert',
        'Palette mit 36 Säcke à 15 kg' => 'Palette mit 36 Säcken à 15 kg',
        'Palette mit 66 Säcke à 15 kg' => 'Palette mit 66 Säcken à 15 kg',
        'Palette mit 65 Säcke à 15 kg' => 'Palette mit 65 Säcken à 15 kg',
        'Palette mit 70 Säcke à 15 kg' => 'Palette mit 70 Säcken à 15 kg',
        'Palette mit 70 Säcke à 15Kg' => 'Palette mit 70 Säcken à 15 kg',
        'Palette mit 70 Säcke à 15kg' => 'Palette mit 70 Säcken à 15 kg',
        'Palette mit 66 Säcke à 15kg' => 'Palette mit 66 Säcken à 15 kg',
        'Palette mit 65 Säcke à 15kg' => 'Palette mit 65 Säcken à 15 kg',
        'Palette mit 65 Säcke x 15kg' => 'Palette mit 65 Säcken à 15 kg',
        'Palette 65 Säcke x 15kg' => 'Palette 65 Säcke à 15 kg',
        'Palette mit 36 Säcke à 15 kg' => 'Palette mit 36 Säcken à 15 kg',
        'Palette 40 Netze à Scheite 25kg' => 'Palette 40 Netze à Scheite 25 kg',
        'Verpackt in reissfesten Säcken' => 'Verpackt in reißfesten Säcken',
        'reißfesten Säcken à 15 kg' => 'reißfesten Säcken zu je 15 kg',
        'Diffusion mehrere Räume' => 'Wärmeverteilung auf mehrere Räume',
        'Reconnaissance der combustible' => 'Brennstofferkennung',
        'Masse volumique: ≥ 600 kg.m-3' => 'Rohdichte: ≥ 600 kg/m³',
        'Sur brut (% en masse auf masse feucht)' => 'bezogen auf Feuchtmasse (Masse-%)',
        'auf brut (% en masse auf masse feucht)' => 'bezogen auf Feuchtmasse (Masse-%)',
        'Masse je Kiste' => 'Maße je Kiste',
        'die Masse 120,5' => 'die Maße 120,5',
        'Palettenmasse' => 'Palettenmaße',
        'Palettenmass' => 'Palettenmaß',
        'Kistenmasse' => 'Kistenmaße',
        'Massnahme' => 'Maßnahme',
    ];

    /**
     * @var array<string, string>
     */
    private const SWISS_SPELLING = [
        'gleichmässig' => 'gleichmäßig',
        'mässig' => 'mäßig',
        'gemäss' => 'gemäß',
        'gross' => 'groß',
        'Gross' => 'Groß',
        'gröss' => 'größ',
        'Gröss' => 'Größ',
        'schliess' => 'schließ',
        'Schliess' => 'Schließ',
        'ausser' => 'außer',
        'Ausser' => 'Außer',
        'Aussen' => 'Außen',
        'aussen' => 'außen',
        'Strasse' => 'Straße',
        'strasse' => 'straße',
        'Weiss' => 'Weiß',
        'weiss' => 'weiß',
        'heiss' => 'heiß',
        'Heiss' => 'Heiß',
        'reissfest' => 'reißfest',
        'Geniess' => 'Genieß',
        'geniess' => 'genieß',
        'Fuss' => 'Fuß',
        'fuss' => 'fuß',
        'Russbildung' => 'Rußbildung',
        'schweiss' => 'schweiß',
        'Schweiss' => 'Schweiß',
    ];

    public static function normalize(string $text): string
    {
        if ($text === '') {
            return $text;
        }

        $text = preg_replace(
            '/Absence d[’\']écorce Abrasion\s*(?:<br\s*\/?>)?\s*minimale in der Sack \(pas de Staub\)/iu',
            'Rindenfrei, minimaler Abrieb im Sack (kein Staub)',
            $text
        ) ?? $text;

        $text = preg_replace(
            '/\s*(?:<i class="fa fa-check"><\/i>\s*)?Lieferung gratuite \(5 à 8 jours ouvrables\)/u',
            ' Kostenlose Lieferung nach Deutschland in 2 bis 5 Werktagen',
            $text
        ) ?? $text;

        $text = strtr($text, self::PHRASES);

        return strtr($text, self::SWISS_SPELLING);
    }
}
