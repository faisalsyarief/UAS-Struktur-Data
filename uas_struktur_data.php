<?php

$rules = [
  ["if" => ["pendapatan" => "> 10000000", "riwayat_kredit" => "baik", "jaminan" => "ya"], "then" => "Disetujui"],
  ["if" => ["pendapatan" => ">= 5000000", "pendapatan" => "<= 10000000", "status_pekerjaan" => "tetap", "rasio_utang" => "<= 35"], "then" => "Disetujui"],
  ["if" => ["pendapatan" => ">= 5000000", "pendapatan" => "<= 10000000", "status_pekerjaan" => "tetap", "rasio_utang" => "> 35"], "then" => "Ditolak"],
  ["if" => ["rasio_utang" => "> 40", "riwayat_kredit" => "buruk"], "then" => "Ditolak"],
  ["if" => ["pendapatan" => "< 5000000"], "then" => "Ditolak"],
  ["if" => ["jumlah_pinjaman" => "> pendapatan * 5"], "then" => "Ditolak"]
];

$facts1 = [
  "pendapatan" => 10000000,
  "riwayat_kredit" => "baik",
  "jumlah_pinjaman" => 50000000,
  "durasi" => 24,
  "jaminan" => "ya",
  "rasio_utang" => 20,
  "status_pekerjaan" => "tetap",
  "usia" => 30
];

$facts2 = [
  "pendapatan" => 4000000,
  "riwayat_kredit" => "",
  "jumlah_pinjaman" => "",
  "durasi" => "",
  "jaminan" => "",
  "rasio_utang" => 36,
  "status_pekerjaan" => "tetap",
  "usia" => ""

];

function forwardChaining($facts, $rules)
{
  $factsN = $facts;
  $hasil = [];

  do {
    $tambahfact = false;

    foreach ($rules as $rule) {
      $conditionMet = true;

      foreach ($rule["if"] as $key => $value) {
        if (preg_match('/([<>]=?|==)\s*(\d+)/', $value, $matches)) {
          $operator = $matches[1];
          $compareValue = (int) $matches[2];

          if (!isset($factsN[$key]) || !eval ("return {$factsN[$key]} $operator $compareValue;")) {
            $conditionMet = false;
            break;
          }
        } elseif (!isset($factsN[$key]) || $factsN[$key] != $value) {
          $conditionMet = false;
          break;
        }
      }

      if ($conditionMet && !in_array($rule["then"], $factsN)) {
        $factsN[] = $rule["then"];
        $hasil[] = $rule["then"];
        $tambahfact = true;
      }
    }
  } while ($tambahfact);

  return $hasil;
}

$hasil1 = forwardChaining($facts1, $rules);
$hasil2 = forwardChaining($facts2, $rules);

echo "Kesimpulan yang dihasilkan 1: <br>";
echo implode(", ", $hasil1);
echo "<br>Kesimpulan yang dihasilkan 2: <br>";
echo implode(", ", $hasil2);

?>
