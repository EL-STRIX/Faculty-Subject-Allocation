<?php
session_start();
if (!isset($_SESSION['hod_authenticated'])) {
    header('Location: ../hod-dashboard.php');
    exit;
}

require_once '../config/db.php';

// Fetch data
$result = $conn->query("SELECT * FROM faculty_submissions ORDER BY submitted_at ASC");
$rows = [];
$sr = 1;
while ($row = $result->fetch_assoc()) {
    $rows[] = [
        $sr++,
        $row['faculty_name'],
        $row['subject_1'],
        $row['subject_2'],
        $row['subject_3'],
        $row['subject_4'],
        date('d M Y, h:i A', strtotime($row['submitted_at']))
    ];
}
$conn->close();

// ── Build a proper .xlsx using ZipArchive + XML ──

$filename = 'brainware_faculty_allocation_' . date('Y-m-d') . '.xlsx';

// ── Shared strings ──
$headers = ['Sr. No.', 'Faculty Name', 'Subject 1', 'Subject 2', 'Subject 3', 'Subject 4', 'Submitted At'];

// Collect all unique strings for shared strings table
$stringTable = [];
$strIndex = [];

function addStr($s, &$stringTable, &$strIndex) {
    $s = (string)$s;
    if (!isset($strIndex[$s])) {
        $strIndex[$s] = count($stringTable);
        $stringTable[] = $s;
    }
    return $strIndex[$s];
}

// Register headers
foreach ($headers as $h) addStr($h, $stringTable, $strIndex);
// Register all row strings (faculty name, subjects, date)
foreach ($rows as $row) {
    addStr($row[1], $stringTable, $strIndex); // faculty name
    for ($i = 2; $i <= 5; $i++) addStr($row[$i], $stringTable, $strIndex); // subjects
    addStr($row[6], $stringTable, $strIndex); // date
}

// ── [Content_Types].xml ──
$contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml" ContentType="application/xml"/>
  <Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>
  <Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>
  <Override PartName="/xl/sharedStrings.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sharedStrings+xml"/>
  <Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>
</Types>';

// ── _rels/.rels ──
$relsRoot = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>
</Relationships>';

// ── xl/_rels/workbook.xml.rels ──
$relsWorkbook = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>
  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/sharedStrings" Target="sharedStrings.xml"/>
  <Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>
</Relationships>';

// ── xl/workbook.xml ──
$workbook = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"
          xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
  <sheets>
    <sheet name="Faculty Allocation" sheetId="1" r:id="rId1"/>
  </sheets>
</workbook>';

// ── xl/styles.xml ──
// Style index 0 = normal, 1 = header (bold, blue fill, white font), 2 = number
$styles = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
  <fonts count="3">
    <font><sz val="11"/><name val="Calibri"/></font>
    <font><b/><sz val="11"/><color rgb="FFFFFFFF"/><name val="Calibri"/></font>
    <font><sz val="11"/><name val="Calibri"/></font>
  </fonts>
  <fills count="3">
    <fill><patternFill patternType="none"/></fill>
    <fill><patternFill patternType="gray125"/></fill>
    <fill><patternFill patternType="solid"><fgColor rgb="FF1E3A5F"/></patternFill></fill>
  </fills>
  <borders count="2">
    <border><left/><right/><top/><bottom/><diagonal/></border>
    <border>
      <left style="thin"><color rgb="FFBFCCD9"/></left>
      <right style="thin"><color rgb="FFBFCCD9"/></right>
      <top style="thin"><color rgb="FFBFCCD9"/></top>
      <bottom style="thin"><color rgb="FFBFCCD9"/></bottom>
      <diagonal/>
    </border>
  </borders>
  <cellStyleXfs count="1">
    <xf numFmtId="0" fontId="0" fillId="0" borderId="0"/>
  </cellStyleXfs>
  <cellXfs count="3">
    <xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyBorder="1"/>
    <xf numFmtId="0" fontId="1" fillId="2" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1">
      <alignment horizontal="center" vertical="center"/>
    </xf>
    <xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyBorder="1">
      <alignment horizontal="center"/>
    </xf>
  </cellXfs>
</styleSheet>';

// ── xl/sharedStrings.xml ──
$ssXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<sst xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" count="' . count($stringTable) . '" uniqueCount="' . count($stringTable) . '">';
foreach ($stringTable as $s) {
    $ssXml .= '<si><t xml:space="preserve">' . htmlspecialchars($s, ENT_XML1, 'UTF-8') . '</t></si>';
}
$ssXml .= '</sst>';

// ── xl/worksheets/sheet1.xml ──
$colLetters = ['A','B','C','D','E','F','G'];
$totalRows = count($rows) + 1; // +1 for header

// Column widths
$colWidths = [8, 28, 30, 30, 30, 30, 22];

$sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
  <sheetFormatPr defaultRowHeight="18"/>
  <cols>';
foreach ($colWidths as $ci => $w) {
    $cn = $ci + 1;
    $sheetXml .= '<col min="' . $cn . '" max="' . $cn . '" width="' . $w . '" customWidth="1"/>';
}
$sheetXml .= '</cols><sheetData>';

// Header row (style 1 = bold blue)
$sheetXml .= '<row r="1" ht="22" customHeight="1">';
foreach ($headers as $ci => $h) {
    $col = $colLetters[$ci] . '1';
    $si  = $strIndex[$h];
    $sheetXml .= '<c r="' . $col . '" t="s" s="1"><v>' . $si . '</v></c>';
}
$sheetXml .= '</row>';

// Data rows
foreach ($rows as $ri => $row) {
    $r = $ri + 2;
    $sheetXml .= '<row r="' . $r . '">';
    // Sr. No. (number, centered, style 2)
    $sheetXml .= '<c r="A' . $r . '" t="n" s="2"><v>' . $row[0] . '</v></c>';
    // Faculty name (string, style 0)
    $si = $strIndex[$row[1]];
    $sheetXml .= '<c r="B' . $r . '" t="s" s="0"><v>' . $si . '</v></c>';
    // Subjects (strings, style 0)
    for ($col = 2; $col <= 5; $col++) {
        $letter = $colLetters[$col];
        $si = $strIndex[$row[$col]];
        $sheetXml .= '<c r="' . $letter . $r . '" t="s" s="0"><v>' . $si . '</v></c>';
    }
    // Date (string, style 0)
    $si = $strIndex[$row[6]];
    $sheetXml .= '<c r="G' . $r . '" t="s" s="0"><v>' . $si . '</v></c>';
    $sheetXml .= '</row>';
}

$sheetXml .= '</sheetData>';

// Auto filter on header row
$lastCol = 'G';
$sheetXml .= '<autoFilter ref="A1:' . $lastCol . '1"/>';
$sheetXml .= '</worksheet>';

// ── Write to temp file then output ──
$tmpFile = tempnam(sys_get_temp_dir(), 'xlsx_');
$zip = new ZipArchive();
$zip->open($tmpFile, ZipArchive::OVERWRITE);
$zip->addFromString('[Content_Types].xml',            $contentTypes);
$zip->addFromString('_rels/.rels',                    $relsRoot);
$zip->addFromString('xl/workbook.xml',                $workbook);
$zip->addFromString('xl/_rels/workbook.xml.rels',     $relsWorkbook);
$zip->addFromString('xl/styles.xml',                  $styles);
$zip->addFromString('xl/sharedStrings.xml',           $ssXml);
$zip->addFromString('xl/worksheets/sheet1.xml',       $sheetXml);
$zip->close();

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . filesize($tmpFile));
header('Pragma: no-cache');
header('Expires: 0');

readfile($tmpFile);
unlink($tmpFile);
exit;
?>
