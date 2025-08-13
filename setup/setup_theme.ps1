# Define the flag file.
$flagFile = ".theme_setup_done"

# Exit if setup was already completed.
if (Test-Path $flagFile) {
    Write-Host "Theme setup has already been completed. Skipping..."
    exit
}

# Prompt for theme selection.
function Prompt-ThemeSelection {
    do {
        Write-Host "Which theme do you want to use?"
        Write-Host "1) WDS-BT"
        Write-Host "2) Ollie"
        $themeChoice = Read-Host "Enter 1 or 2"
        $valid = $themeChoice -eq '1' -or $themeChoice -eq '2'
        if (-not $valid) {
            Write-Host "Invalid choice. Please enter 1 for WDS-BT or 2 for Ollie."
        }
    } until ($valid)
    return $themeChoice
}

$themeChoice = Prompt-ThemeSelection

if ($themeChoice -eq '2') {
    # Ollie theme logic.
    $ollieThemeDir = "themes/ollie"
    $ollieGithubUrl = "https://github.com/OllieWP/ollie.git"
    $ollieBranch = Read-Host "Enter the branch or tag to use for Ollie (default: main)"
    if ([string]::IsNullOrWhiteSpace($ollieBranch)) { $ollieBranch = "main" }

    if (Test-Path $ollieThemeDir) {
        Remove-Item -Recurse -Force $ollieThemeDir
    }

    # Clone Ollie theme from GitHub (install theme first).
    git clone --depth=1 --branch $ollieBranch $ollieGithubUrl $ollieThemeDir
    if ($LASTEXITCODE -ne 0) {
        Write-Error "Failed to clone Ollie theme from GitHub."
        exit 1
    }
    Remove-Item -Recurse -Force (Join-Path $ollieThemeDir ".git")

    # Symlink .nvmrc if not present.
    if (-not (Test-Path (Join-Path $ollieThemeDir ".nvmrc"))) {
        if (Test-Path ".nvmrc") {
            New-Item -ItemType SymbolicLink -Path (Join-Path $ollieThemeDir ".nvmrc") -Target (Resolve-Path ".nvmrc") | Out-Null
            Write-Host ".nvmrc not found in $ollieThemeDir, symlinked from project root."
        } else {
            Write-Host "Warning: No .nvmrc found in project root to symlink."
        }
    }

    # Prompt for project prefix and name for replacements.
    function Prompt-ProjectPrefix {
        do {
            $projectPrefix = Read-Host "Enter the project prefix (e.g., ollie_child)"
            $valid = $projectPrefix -match '^[a-z0-9]([_]?[a-z0-9]+)*$'
            if (-not $valid) {
                Write-Host "Invalid project prefix. It should start and end with an alphanumeric character and may contain underscores."
            }
        } until ($valid)
        return $projectPrefix
    }
    function Prompt-ProjectName {
        do {
            $projectName = Read-Host "Enter the project name (e.g., Ollie Child)"
            $valid = $projectName -match '^[A-Za-z0-9]([ _]?[A-Za-z0-9]+)*$'
            if (-not $valid) {
                Write-Host "Invalid project name. It should start and end with an alphanumeric character and may contain underscores and spaces."
            }
        } until ($valid)
        return $projectName
    }
    $projectPrefix = Prompt-ProjectPrefix
    $projectName = Prompt-ProjectName

    # Do NOT require olliewp/ollie via composer.
    # Only require Ollie Pro plugin to composer.json (after theme is installed).
    composer require webdevstudios/ollie-pro: "*"

    # Only do search/replace in composer.json, README.md, and package.json (not in the theme directory).
    $composerJson = "composer.json"
    if (Test-Path $composerJson) {
        (Get-Content $composerJson) |
            ForEach-Object {
                $_ -replace 'project_prefix', $projectPrefix `
                   -replace 'THEME_PLACEHOLDER', $projectPrefix
            } | Set-Content $composerJson
    }
    $readme = "README.md"
    if (Test-Path $readme) {
        (Get-Content $readme) |
            ForEach-Object {
                $_ -replace 'PROJECT NAME', $projectName `
                   -replace 'project_prefix', $projectPrefix
            } | Set-Content $readme
    }
    $pkgJson = "package.json"
    if (Test-Path $pkgJson) {
        (Get-Content $pkgJson) |
            ForEach-Object {
                $_ -replace 'PROJECTNAME', $projectName `
                   -replace 'wds-project-template', $projectPrefix `
                   -replace 'bt-template', $projectPrefix
            } | Set-Content $pkgJson
    }

    # Mark setup as done.
    New-Item -ItemType File -Path $flagFile -Force | Out-Null
    Write-Host "Ollie theme (GitHub) has been installed."
    Write-Host "The Ollie Pro plugin requires the free Ollie theme to be installed and activated."
    Write-Host "View and install the theme from the WordPress directory: https://wordpress.org/themes/ollie/"
    Write-Host "Or install it from your WordPress admin by searching for 'Ollie' in Appearance > Themes > Add New."
    exit 0
}

# If WDS-BT is chosen, continue as before, but ask about WDS Design System.
do {
    $removeDS = Read-Host "Do you want to remove WDS Design System? (yes/no)"
    $valid = $removeDS -match '^(yes|no|y|n)$'
    if (-not $valid) {
        Write-Host "Please answer yes or no."
    }
} until ($valid)

if ($removeDS -match '^(yes|y)$') {
    $wdsbtBranch = "base"
} else {
    $wdsbtBranch = ""
}

function Prompt-ProjectPrefix {
    do {
        $projectPrefix = Read-Host "Enter the project prefix (e.g., wds_bt)"
        $valid = $projectPrefix -match '^[a-z0-9]([_]?[a-z0-9]+)*$'
        if (-not $valid) {
            Write-Host "Invalid project prefix. It should start and end with an alphanumeric character and may contain underscores."
        }
    } until ($valid)
    return $projectPrefix
}

function Prompt-ProjectName {
    do {
        $projectName = Read-Host "Enter the project name (e.g., WDS BT)"
        $valid = $projectName -match '^[A-Za-z0-9]([ _]?[A-Za-z0-9]+)*$'
        if (-not $valid) {
            Write-Host "Invalid project name. It should start and end with an alphanumeric character and may contain underscores and spaces."
        }
    } until ($valid)
    return $projectName
}

$projectPrefix = Prompt-ProjectPrefix
$projectName = Prompt-ProjectName

$themeRepo = "git@github.com:WebDevStudios/wds-bt.git"
$themesDir = "themes"
$projectDir = Join-Path $themesDir $projectPrefix

if (-not (Test-Path $themesDir)) {
    Write-Error "The directory '$themesDir' does not exist."
    exit 1
}

if (Test-Path $projectDir) {
    Write-Error "The directory '$projectDir' already exists."
    exit 1
}

if (Test-Path "$themesDir/wds_bt") {
    Remove-Item -Recurse -Force "$themesDir/wds_bt"
}

# Clone WDS-BT repository into the themes directory with depth 1.
if ($wdsbtBranch) {
    git clone --depth=1 -b $wdsbtBranch $themeRepo $projectDir
} else {
    git clone --depth=1 $themeRepo $projectDir
}
if ($LASTEXITCODE -ne 0) {
    Write-Error "Failed to clone the WDS-BT repository."
    exit 1
}

# Symlink .nvmrc if not present.
if (-not (Test-Path (Join-Path $projectDir ".nvmrc"))) {
    if (Test-Path ".nvmrc") {
        New-Item -ItemType SymbolicLink -Path (Join-Path $projectDir ".nvmrc") -Target (Resolve-Path ".nvmrc") | Out-Null
        Write-Host ".nvmrc not found in $projectDir, symlinked from project root."
    } else {
        Write-Host "Warning: No .nvmrc found in project root to symlink."
    }
}

Remove-Item -Recurse -Force (Join-Path $projectDir ".git")

Set-Location $projectDir

# Replace all variations of placeholders.
$replacements = @{
    'wds-bt' = $projectPrefix
    'wdsbt' = $projectPrefix -replace '_', ''
    'WDS BT' = $projectName
    'WDSBT' = $projectName -replace ' ', ''
}

Get-ChildItem -Recurse -File | ForEach-Object {
    $file = $_.FullName
    foreach ($pattern in $replacements.Keys) {
        (Get-Content $file) -replace $pattern, $replacements[$pattern] | Set-Content $file
    }
}

# Update style.css.
$styleCss = "style.css"
if (Test-Path $styleCss) {
    (Get-Content $styleCss) -replace '^(Theme Name:).+$', "`$1 $projectName" | Set-Content $styleCss
}

Set-Location ../..

# Update composer.json.
$composerJson = "composer.json"
if (Test-Path $composerJson) {
    (Get-Content $composerJson) -replace '"webdevstudios\/wds-bt": "\*",', '' |
        ForEach-Object { $_ -replace 'project_prefix', $projectPrefix -replace 'THEME_PLACEHOLDER', $projectPrefix } |
        Set-Content $composerJson

    & jq ".extra.\"installer-paths\" += {\"themes/$projectPrefix/{\$name}/\": [\"type:wordpress-theme\"]}" $composerJson | Set-Content $composerJson
    & jq ".scripts.build[1] = \"cd themes/$projectPrefix && npm run setup --silent\" |
          .scripts.format[1] = \"cd themes/$projectPrefix && npm run format\" |
          .scripts.lint[1] = \"cd themes/$projectPrefix && npm run lint\" |
          .scripts.watch[1] = \"cd themes/$projectPrefix && npm run start\"" $composerJson | Set-Content $composerJson
}

# Update README.md.
$readme = "README.md"
if (Test-Path $readme) {
    (Get-Content $readme) |
        ForEach-Object {
            $_ -replace 'PROJECT NAME', $projectName `
               -replace 'project_prefix', $projectPrefix `
               -replace 'wds-bt', $projectPrefix `
               -replace 'WDS-BT', $projectName `
               -replace 'wdsbt', $projectPrefix
        } | Set-Content $readme
}

# Update package.json.
$pkgJson = "package.json"
if (Test-Path $pkgJson) {
    (Get-Content $pkgJson) |
        ForEach-Object {
            $_ -replace 'PROJECTNAME', $projectName `
               -replace 'wds-project-template', $projectPrefix `
               -replace 'bt-template', $projectPrefix `
               -replace 'wds-bt', $projectPrefix `
               -replace 'WDS-BT', $projectName `
               -replace 'wdsbt', $projectPrefix
        } | Set-Content $pkgJson
}

# Update workflow files.
$workflowDir = ".github/workflows"
if (Test-Path $workflowDir) {
    Get-ChildItem -Recurse -Path $workflowDir -File | ForEach-Object {
        (Get-Content $_.FullName) |
            ForEach-Object {
                $_ -replace 'wds-bt', $projectPrefix -replace 'WDS-BT', $projectName
            } | Set-Content $_.FullName
    }
}

# Install dependencies.
composer install
npm install --loglevel=error

# Run theme setup.
Set-Location $projectDir
npm install --loglevel=error
npm run setup --silent
if ($LASTEXITCODE -ne 0) {
    Write-Error "Failed to run theme setup."
    exit 1
}

# Ensure Lefthook is installed.
if (-not (Test-Path "node_modules/.bin/lefthook")) {
    npm install lefthook --loglevel=error --no-audit --no-fund
}

# Install Lefthook hooks.
npx lefthook install
if ($LASTEXITCODE -ne 0) {
    Write-Error "Failed to install Lefthook hooks."
    exit 1
}

# Mark setup as complete.
New-Item -ItemType File -Path "../$flagFile" -Force | Out-Null

Write-Host "Project theme setup complete."
