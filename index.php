<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulateur IR Sénégal (Basé sur le CGI)</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f4f9; padding: 20px; }
        .container { max-width: 700px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        h2 { color: #2c3e50; text-align: center; }
        label { font-weight: bold; display: block; margin-top: 15px; }
        input, select { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;}
        button { background-color: #27ae60; color: white; border: none; padding: 15px; width: 100%; margin-top: 20px; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button:hover { background-color: #219150; }
        .resultat { margin-top: 25px; padding: 20px; background-color: #e8f6f3; border-left: 5px solid #27ae60; }
        .detail { font-size: 0.9em; color: #555; margin-top: 5px; }
        .reference { font-size: 0.8em; color: #999; font-style: italic; }
    </style>
</head>
<body>

<div class="container">
    <h2>🇸🇳 Simulateur d'Impôt sur le Revenu</h2>
    <p>Calcul basé sur le barème progressif et le quotient familial.</p>

    <form method="POST">
        <label for="salaire_annuel">Revenu Salarial ANNUEL Brut (FCFA) :</label>
        <input type="number" name="salaire_annuel" required placeholder="Ex: 5000000">

        <label for="parts">Nombre de parts (Quotient Familial) :</label>
        <select name="parts">
            <option value="1">1 part (Célibataire)</option>
            <option value="1.5">1.5 parts</option>
            <option value="2">2 parts (Marié sans enfant)</option>
            <option value="2.5">2.5 parts (Marié + 1 enfant)</option>
            <option value="3">3 parts (Marié + 2 enfants)</option>
            <option value="3.5">3.5 parts (Marié + 3 enfants)</option>
            <option value="4">4 parts (Marié + 4 enfants)</option>
            <option value="4.5">4.5 parts (Marié + 5 enfants)</option>
            <option value="5">5 parts (Marié + 6 enfants et plus)</option>
        </select>

        <button type="submit" name="calculer">Calculer l'Impôt (IR)</button>
    </form>

    <?php
    if (isset($_POST['calculer'])) {
        $salaire_brut = floatval($_POST['salaire_annuel']);
        $parts = strval($_POST['parts']); // String pour correspondre aux clés du tableau

        // --- ÉTAPE 1 : DÉTERMINATION DU REVENU NET IMPOSABLE ---
        // Application de l'abattement forfaitaire de 30% plafonné à 900.000
        // Source : "Un abattement forfaitaire de 30%... sans dépassée 900.000" 
        
        $abattement = $salaire_brut * 0.30;
        if ($abattement > 900000) {
            $abattement = 900000;
        }
        
        $revenu_net_imposable = $salaire_brut - $abattement;

        // --- ÉTAPE 2 : CALCUL DE L'IMPÔT BRUT (BARÈME PROGRESSIF) ---
        // Source : Tableau Page 6 "Le taux d'imposition de l'IR" [cite: 48, 49]
        
        $impot_brut = 0;
        
        // Tranche 1: 0 à 630.000 (0%)
        // Tranche 2: 630.001 à 1.500.000 (20%)
        if ($revenu_net_imposable > 630000) {
            $tranche = min($revenu_net_imposable, 1500000) - 630000;
            $impot_brut += $tranche * 0.20;
        }
        
        // Tranche 3: 1.500.001 à 4.000.000 (30%)
        if ($revenu_net_imposable > 1500000) {
            $tranche = min($revenu_net_imposable, 4000000) - 1500000;
            $impot_brut += $tranche * 0.30;
        }

        // Tranche 4: 4.000.001 à 8.000.000 (35%)
        if ($revenu_net_imposable > 4000000) {
            $tranche = min($revenu_net_imposable, 8000000) - 4000000;
            $impot_brut += $tranche * 0.35;
        }

        // Tranche 5: 8.000.001 à 13.500.000 (37%)
        if ($revenu_net_imposable > 8000000) {
            $tranche = min($revenu_net_imposable, 13500000) - 8000000;
            $impot_brut += $tranche * 0.37;
        }

        // Tranche 6: Plus de 13.500.000 (40%)
        if ($revenu_net_imposable > 13500000) {
            $tranche = $revenu_net_imposable - 13500000;
            $impot_brut += $tranche * 0.40;
        }

        // --- ÉTAPE 3 : RÉDUCTION POUR CHARGE DE FAMILLE ---
        // Source : Tableau Page 7 
        // "Sur le montant de [l'impôt], il est appliqué au charge de famille..."
        
        // Définition des règles (Taux, Min, Max) selon le tableau du cours
        $regles_famille = [
            "1"   => ["taux" => 0,    "min" => 0,      "max" => 0],
            "1.5" => ["taux" => 0.10, "min" => 100000, "max" => 300000],
            "2"   => ["taux" => 0.15, "min" => 200000, "max" => 650000],
            "2.5" => ["taux" => 0.20, "min" => 300000, "max" => 1100000],
            "3"   => ["taux" => 0.25, "min" => 400000, "max" => 1650000],
            "3.5" => ["taux" => 0.30, "min" => 500000, "max" => 2400000],
            "4"   => ["taux" => 0.35, "min" => 600000, "max" => 2490000],
            "4.5" => ["taux" => 0.40, "min" => 700000, "max" => 2755000],
            "5"   => ["taux" => 0.45, "min" => 800000, "max" => 3180000]
        ];

        $regle = $regles_famille[$parts];
        
        // Calcul de la réduction théorique
        $reduction = $impot_brut * $regle['taux'];
        
        // Application des bornes Min/Max (seulement si l'impôt brut est positif)
        if ($impot_brut > 0 && $parts != "1") {
            // Si la réduction calculée est inférieure au min, on prend le min
            if ($reduction < $regle['min']) {
                $reduction = $regle['min'];
            }
            // Si la réduction dépasse le max, on la plafonne au max
            if ($reduction > $regle['max']) {
                $reduction = $regle['max'];
            }
            // La réduction ne peut pas dépasser l'impôt lui-même (l'impôt ne peut être négatif)
            if ($reduction > $impot_brut) {
                $reduction = $impot_brut;
            }
        } else {
            $reduction = 0;
        }

        $impot_net = $impot_brut - $reduction;

        // --- AFFICHAGE ---
        echo "<div class='resultat'>";
        echo "<h3>Résultat de l'analyse fiscale :</h3>";
        echo "<div class='detail'>Revenu Brut Annuel : <strong>" . number_format($salaire_brut, 0, ',', ' ') . " FCFA</strong></div>";
        echo "<div class='detail'>Abattement (30%, max 900k) : -" . number_format($abattement, 0, ',', ' ') . " FCFA</div>";
        echo "<div class='detail'>Revenu Net Imposable : <strong>" . number_format($revenu_net_imposable, 0, ',', ' ') . " FCFA</strong></div>";
        echo "<hr>";
        echo "<div class='detail'>Impôt Brut (avant réduction familiale) : " . number_format($impot_brut, 0, ',', ' ') . " FCFA</div>";
        echo "<div class='detail'>Réduction familiale ({$parts} parts) : -" . number_format($reduction, 0, ',', ' ') . " FCFA</div>";
        echo "<h4 style='color:#c0392b; margin-top:15px'>IMPÔT NET À PAYER (Annuel) : " . number_format($impot_net, 0, ',', ' ') . " FCFA</h4>";
        echo "<div class='detail'>Soit par mois : " . number_format($impot_net/12, 0, ',', ' ') . " FCFA</div>";
        echo "</div>";
    }
    ?>
</div>

</body>
</html>