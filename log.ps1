Write-Host "Waiting for logs..."
Get-Content -Tail 0 logs/error.log -Wait