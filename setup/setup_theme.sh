#!/bin/bash

# Detect OS.
OS=$(uname)

# Define the flag file path.
flag_file=".theme_setup_done"

# Check if the setup has already been done.
if [ -f "$flag_file" ]; then
    echo "Theme setup has already been completed. Skipping..."
    exit 0
fi

# Regular expression pattern for validation (alphanumeric and optional underscores).
regex_prefix="^[a-z0-9]([_]?[a-z0-9]+)*$"

regex_pattern="^[A-Za-z0-9]([ _]?[A-Za-z0-9]+)*$"

# Function to prompt for project prefix with validation.
prompt_project_prefix() {
    while true; do
        read -p "Enter the project prefix (e.g., alphanumeric and optional underscores - e.g., wds_bt): " project_prefix
        if [[ "$project_prefix" =~ $regex_prefix ]]; then
            break
        else
            echo "Invalid project prefix. It should start and end with an alphanumeric character, and may contain '_'."
        fi
    done
}

# Function to prompt for project name with validation.
prompt_project_name() {
    while true; do
        read -p "Enter the project name (e.g., alphanumeric and optional underscores - e.g., WDS BT). It should start with a capital letter: " project_name
        if [[ "$project_name" =~ $regex_pattern ]]; then
            break
        else
            echo "Invalid project name. It should start and end with an alphanumeric character, and may contain '_' and/or spaces."
        fi
    done
}

# Prompt for theme selection.
while true; do
    echo "Which theme do you want to use?"
    echo "1) WDS-BT"
    echo "2) Ollie"
    read -p "Enter 1 or 2: " theme_choice
    if [[ "$theme_choice" == "1" || "$theme_choice" == "2" ]]; then
        break
    else
        echo "Invalid choice. Please enter 1 for WDS-BT or 2 for Ollie."
    fi
done

if [[ "$theme_choice" == "2" ]]; then
    # Ollie theme logic (GitHub only)
    ollie_theme_dir="themes/ollie"
    ollie_github_url="https://github.com/OllieWP/ollie.git"

    # Prompt for branch or tag.
    read -p "Enter the branch or tag to use for Ollie (default: main): " ollie_branch
    ollie_branch=${ollie_branch:-main}

    # Remove any existing Ollie theme directory.
    if [ -d "$ollie_theme_dir" ]; then
        rm -rf "$ollie_theme_dir"
    fi

    # Clone Ollie theme from GitHub (install theme first).
    git clone --depth=1 --branch "$ollie_branch" "$ollie_github_url" "$ollie_theme_dir"
    if [ $? -ne 0 ]; then
        echo "Error: Failed to clone Ollie theme from GitHub."
        exit 1
    fi
    rm -rf "$ollie_theme_dir/.git"

    # Ensure .nvmrc exists in Ollie theme directory, symlink from project root if not found.
    if [ ! -f "$ollie_theme_dir/.nvmrc" ]; then
        if [ -f ".nvmrc" ]; then
            ln -s "$(pwd)/.nvmrc" "$ollie_theme_dir/.nvmrc"
            echo ".nvmrc not found in $ollie_theme_dir, symlinked from project root."
        else
            echo "Warning: No .nvmrc found in project root to symlink."
        fi
    fi

    # Prompt for project prefix and name for replacements.
    while true; do
        read -p "Enter the project prefix (e.g., ollie_child): " project_prefix
        if [[ "$project_prefix" =~ $regex_prefix ]]; then
            break
        else
            echo "Invalid project prefix. It should start and end with an alphanumeric character, and may contain '_'."
        fi
    done
    while true; do
        read -p "Enter the project name (e.g., Ollie Child). It should start with a capital letter: " project_name
        if [[ "$project_name" =~ $regex_pattern ]]; then
            break
        else
            echo "Invalid project name. It should start and end with an alphanumeric character, and may contain '_' and/or spaces."
        fi
    done

    # Do NOT require olliewp/ollie via composer.
    # Only require Ollie Pro plugin to composer.json (after theme is installed).
    composer require webdevstudios/ollie-pro:"*"

    # Only do search/replace in composer.json, README.md, and package.json (not in the theme directory).
    composer_json="composer.json"
    if [ -f "$composer_json" ]; then
        sed -i.bak "s/project_prefix/$project_prefix/g" "$composer_json"
        sed -i.bak "s/THEME_PLACEHOLDER/$project_prefix/g" "$composer_json"
        rm "${composer_json}.bak"
    fi
    readme_file="README.md"
    if [ -f "$readme_file" ]; then
        sed -i.bak "s/PROJECT NAME/$project_name/g" "$readme_file"
        sed -i.bak "s/project_prefix/$project_prefix/g" "$readme_file"
        rm "${readme_file}.bak"
    fi
    package_file="package.json"
    if [ -f "$package_file" ]; then
        sed -i.bak "s/PROJECTNAME/$project_name/g" "$package_file"
        sed -i.bak "s/wds-project-template/$project_prefix/g" "$package_file"
        sed -i.bak "s/bt-template/$project_prefix/g" "$package_file"
        rm "${package_file}.bak"
    fi

    # Mark setup as done.
    touch "$flag_file"
    echo "Ollie theme (GitHub) has been installed."
    echo "The Ollie Pro plugin requires the free Ollie theme to be installed and activated."
    echo "View and install the theme from the WordPress directory: https://wordpress.org/themes/ollie/"
    echo "Or install it from your WordPress admin by searching for 'Ollie' in Appearance > Themes > Add New."
    exit 0
fi

# If WDS-BT is chosen, continue as before, but ask about WDS Design System.
while true; do
    read -p "Do you want to remove WDS Design System? (yes/no): " remove_ds
    case $remove_ds in
        [Yy]* ) wdsbt_branch="base"; break;;
        [Nn]* ) wdsbt_branch=""; break;;
        * ) echo "Please answer yes or no.";;
    esac
done

if [[ "$OS" == "Linux" || "$OS" == "Darwin" ]]; then
    prompt_project_prefix
    prompt_project_name

    theme_repo_url="git@github.com:WebDevStudios/wds-bt.git"
    themes_dir="themes"
    project_dir="$themes_dir/$project_prefix"

    # Check if the themes directory exists.
    if [ ! -d "$themes_dir" ]; then
        echo "Error: The directory $themes_dir does not exist."
        exit 1
    fi

    # Check if the project directory already exists.
    if [ -d "$project_dir" ]; then
        echo "Error: The directory $project_dir already exists."
        exit 1
    fi

    # Remove the themes/wds_bt folder if it exists.
    if [ -d "$themes_dir/wds_bt" ]; then
        rm -rf "$themes_dir/wds_bt"
    fi

    # Set theme_repo_url based on branch/tag.
    theme_repo_url="git@github.com:WebDevStudios/wds-bt.git"

    # Clone WDS-BT repository into the themes directory with depth 1.
    if [ -n "$wdsbt_branch" ]; then
        git clone --depth=1 -b "$wdsbt_branch" "$theme_repo_url" "$project_dir"
    else
        git clone --depth=1 "$theme_repo_url" "$project_dir"
    fi
    if [ $? -ne 0 ]; then
        echo "Error: Failed to clone the WDS-BT repository."
        exit 1
    fi

    # Ensure .nvmrc exists in WDS-BT theme directory, symlink from project root if not found.
    if [ ! -f "$project_dir/.nvmrc" ]; then
        if [ -f ".nvmrc" ]; then
            ln -s "$(pwd)/.nvmrc" "$project_dir/.nvmrc"
            echo ".nvmrc not found in $project_dir, symlinked from project root."
        else
            echo "Warning: No .nvmrc found in project root to symlink."
        fi
    fi

    # Remove the .git folder from the cloned project.
    rm -rf "$project_dir/.git"

    # Change directory to the cloned project.
    cd "$project_dir" || { echo "Error: Failed to navigate to the project directory."; exit 1; }

    # Replace occurrences in the cloned theme.
    for pattern in 'wds-bt' 'wdsbt' 'WDS BT' 'WDSBT'; do
        grep -rl "$pattern" . | while IFS= read -r file; do
            if [ "$pattern" == 'wds-bt' ]; then
                replacement="$project_prefix"
            elif [ "$pattern" == 'wdsbt' ]; then
                replacement="${project_prefix//_/}"
            else
                replacement="$project_name"
            fi
            sed -i.bak "s#$pattern#$replacement#g" "$file"
            if [ $? -ne 0 ]; then
                echo "Error: Failed to replace '$pattern' with '$replacement' in $file"
                exit 1
            fi

            rm "${file}.bak"
        done
    done

    # Update style.css to reflect the new theme name.
    style_css="style.css"
    if [ -f "$style_css" ];then
        sed -i.bak "s/^Theme Name:.*$/Theme Name: $project_name/" "$style_css"
        rm "${style_css}.bak"
    fi

    # Update theme composer.json to use the new theme folder name and project name.
    cd ../..
    composer_json="composer.json"

    if [ -f "$composer_json" ]; then
        # Remove the line with "webdevstudios/wds-bt": "*".
        sed -i.bak '/"webdevstudios\/wds-bt": "*",/d' "$composer_json"

        # Update with project name.
        sed -i.bak "s/project_prefix/$project_prefix/g" "$composer_json"
        sed -i.bak "s/THEME_PLACEHOLDER/$project_prefix/g" "$composer_json"

        # Update the installer-paths for themes in composer.json.
        jq --arg theme_dir "themes/$project_prefix" --arg project_name "$project_name" \
        '.extra["installer-paths"] |= . + {($theme_dir + "/{$name}/"): ["type:wordpress-theme"]}' \
        "$composer_json" > tmp.$$.json && mv tmp.$$.json "$composer_json"

        # Update the script commands in composer.json to use the new theme directory.
        jq --arg theme_dir "themes/$project_prefix" \
        '.scripts.build[1] = "cd \($theme_dir) && npm run setup --silent" |
         .scripts.format[1] = "cd \($theme_dir) && npm run format" |
         .scripts.lint[1] = "cd \($theme_dir) && npm run lint" |
         .scripts.watch[1] = "cd \($theme_dir) && npm run start"' \
        "$composer_json" > tmp.$$.json && mv tmp.$$.json "$composer_json"

        # Remove backup file created by sed
        rm "${composer_json}.bak"
    fi

    # Update README.md with project name and theme details.
    readme_file="README.md"

    if [ -f "$readme_file" ]; then
        sed -i.bak "s/PROJECT NAME/$project_name/g" "$readme_file"
        sed -i.bak "s/project_prefix/$project_prefix/g" "$readme_file"
        sed -i.bak "s/wds-bt/$project_prefix/g" "$readme_file"
        sed -i.bak "s/WDS-BT/$project_name/g" "$readme_file"
        sed -i.bak "s/wdsbt/$project_prefix/g" "$readme_file"
        rm "${readme_file}.bak"
    fi

    # Update package.json with project name and theme details.
    package_file="package.json"

    if [ -f "$package_file" ]; then
        sed -i.bak "s/PROJECTNAME/$project_name/g" "$package_file"
        sed -i.bak "s/wds-project-template/$project_prefix/g" "$package_file"
        sed -i.bak "s/bt-template/$project_prefix/g" "$package_file"
        sed -i.bak "s/wds-bt/$project_prefix/g" "$package_file"
        sed -i.bak "s/WDS-BT/$project_name/g" "$package_file"
        sed -i.bak "s/wdsbt/$project_prefix/g" "$package_file"
        rm "${package_file}.bak"
    fi

    # Update .github/workflows/ files with project name and theme details.
    workflow_dir=".github/workflows"
    if [ -d "$workflow_dir" ]; then
        grep -rl 'wds-bt' "$workflow_dir" | while IFS= read -r file; do
            sed -i.bak "s/wds-bt/$project_prefix/g" "$file"
            sed -i.bak "s/WDS-BT/$project_name/g" "$file"
            rm "${file}.bak"
        done
    fi

    # Install composer and npm in the project root
    composer install && npm install --loglevel=error

    # Run `npm run setup` in the cloned theme directory.
	cd "$project_dir"
	npm install --loglevel=error
	npm run setup
    if [ $? -ne 0 ]; then
        echo "Error: Failed to run theme setup."
        exit 1
    fi

    # Ensure Lefthook is installed and hooks are set up.
    if [ ! -f "node_modules/.bin/lefthook" ]; then
        npm install lefthook --loglevel=error --no-audit --no-fund
    fi

    # Install Lefthook hooks.
    npx lefthook install
    if [ $? -ne 0 ]; then
        echo "Error: Failed to install Lefthook hooks."
        exit 1
    fi

    # Create the flag file to indicate setup has been done.
    touch "../$flag_file"

    echo "Project theme setup complete."
elif [[ "$OS" == "Windows" ]]; then
    pwsh setup_theme.ps1
else
    echo "Error: Operating system not supported."
    exit 1
fi
