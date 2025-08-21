<?php
$f='app/Providers/HealthScheduleServiceProvider.php';
$s=file_get_contents($f);
if(strpos($s,'tools/db_backup.sh')===false){
  $patched=preg_replace(
    "/->name\\('scheduler\\.heartbeat'\\);/m",
    "->name('scheduler.heartbeat');\n\n        // Daily DB backup 03:30\n        \$schedule->exec(base_path('tools/db_backup.sh'))->dailyAt('03:30');",
    $s,1,$c);
  if($c){
    copy($f,"$f.bak_".date('Ymd_His'));
    file_put_contents($f,$patched);
    echo "[OK] backup scheduled\n";
  } else {
    echo "[WARN] anchor not found; add manually after heartbeat.\n";
  }
} else { echo "[SKIP] already scheduled\n"; }
