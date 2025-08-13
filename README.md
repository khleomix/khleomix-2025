# Khleomix
<!-- Please update the information below with links to your projects -->

<details>
  <summary style="font-size: 20px; font-weight: 700; border-bottom: 1px solid #f3713c;">Table of Contents</summary>
  <ol style="padding: 20px;">
    <li><a href="#requirements">Requirements</a></li>
    <li><a href="#cursor-rules">Cursor Rules</a></li>
    <li><a href="#development">Development</a></li>
    <ul>
      <li><a href="#repo-set-up">Repo Set Up</a></li>
      <li><a href="#local-set-up">Local Set Up</a></li>
      <li><a href="#project-theme-commands">Project Theme Commands</a></li>
      <li><a href="#coding-standards">Coding Standards</a></li>
      <li><a href="#github-workflow-best-practices">GitHub Workflow Best Practices</a></li>
      <li><a href="#release-workflow">Release Workflow</a></li>
      <li><a href="#exception-handling">Exception Handling</a></li>
    </ul>
    <li><a href="#project-management">Project Management</a></li>
    <li><a href="#hosting">Hosting</a></li>
  </ol>
</details>

## Requirements

<a id="requirements"></a>

### Core Requirements
- WordPress 6.4+
- PHP 8.2+
- [Node](https://nodejs.org) (v22+)
- [NPM](https://npmjs.com) (v10+)
- [Local](https://localwp.com/)
- License: [GPLv3](https://www.gnu.org/licenses/gpl-3.0.html)
- [nvm](https://github.com/nvm-sh/nvm)

### Windows Setup Prerequisites
- [Git for Windows](https://git-scm.com/download/win)
- [Node](https://nodejs.org) (v22+)
- [Local](https://localwp.com/)
- [jq for Windows](https://github.com/stedolan/jq/releases) – used for updating composer.json
- [PowerShell 5.1+](https://docs.microsoft.com/en-us/powershell/) or [PowerShell Core](https://github.com/PowerShell/PowerShell)

<p align="right">(<a href="#top">Back to top</a>)</p>

## Cursor Rules

<a id="cursor-rules"></a>

This project enforces development practices through automated Cursor rules. For detailed documentation, see [cursor/rules/README.md](cursor/rules/README.md).

### Branch Protection
Automated rules ensure code quality through branch protection:

- **Main**: Protected branch, changes only through reviewed pull requests
- **Staging**: Requires open PR for merges from main/feature branches
- **Development**: Flexible workflow for active development

### Technical Enforcement
- Node/npm version requirements
- Git hooks via Lefthook
- Branch merge restrictions
- PR requirements

<p align="right">(<a href="#top">Back to top</a>)</p>

## Development

<a id="development"></a>

### Local Set Up

<a id="local-set-up"></a>

Follow these steps to get your project up and running:

1. Clone the __project repository__: `git clone git@github.com:khleomix/khleomix-2025.git wp-content`.
2. Navigate into the project directory: `cd wp-content`.
3. Make the setup script executable: `chmod +x setup.sh`.
4. Run the setup script to get started. This works on macOS, Linux, and Windows. Running `composer run theme-setup` (or executing the setup script `./setup.sh`) now provides an interactive setup process with the following options:

   1. **Theme Selection:**
      - Choose which theme to install:
         - `Khleomix`
         - `Ollie` (OllieWP theme)
   2. **Branch/Tag or Design System Option:**
      - If you select **Khleomix**:
         - You will be asked if you want to remove the WDS Design System (yes/no). Selecting "yes" installs from the `base` branch.
      - If you select **Ollie**:
         - You will be prompted to enter a branch or tag (default: `main`).
   3. **Project Prefix and Name:**
      - For both themes, you will be prompted to enter a project prefix (e.g., `wds_bt` or `ollie_child`) and a project name (e.g., `WDS BT` or `Ollie Child`).

      The script will then:
         - Clone the selected theme (and branch/tag if applicable) into the `themes/` directory.
         - Update `composer.json`, `README.md`, and `package.json` with your chosen project prefix and name.
         - For Ollie, install the Ollie Pro plugin via Composer.
         - For WDSBT:
            - Install Composer and NPM dependencies.
            - Run the theme’s setup script and install Lefthook.

      This interactive setup streamlines project initialization and ensures your environment is configured for your chosen theme and project details.

   > On Windows, the same command (`./setup.sh`) will automatically detect your OS and execute the PowerShell version of the setup script (`setup_theme.ps1`). If you’re using WSL, the Bash version (setup.sh) will run natively.
5. Once complete, your theme will be ready for development.

#### Node Version Management Automation

A new automation script (`setup/preinstall.js`) has been added to streamline Node version management:

- Removes any existing `.nvmrc` file in the project root.
- Creates a symlink from the root `.nvmrc` to `themes/khleomix/.nvmrc`.
- Runs `nvm install` and `nvm use` to ensure the correct Node version is installed and active.

**How it works:**
- The script is executed as part of the setup process.
- It uses a temporary shell script to source `nvm` and run the necessary commands.
- Works on macOS, Linux, and Windows (with nvm for Windows).

**Manual usage:**
If you need to re-run the Node version setup, execute:

```sh
node setup/preinstall.js
```

#### Troubleshooting

- Error: `rimraf: command not found` during theme setup
  - Cause: The theme's setup script calls `npm run reset` before dependencies are installed.
  - Fix: This repository installs theme dependencies before running the setup. If you still encounter this error:
    1. Remove the partially created theme directory (e.g., `rm -rf themes/<your_prefix>`).
    2. Re-run the setup: `composer run theme-setup` (or `./setup.sh`).
    3. If needed, manually install in the theme directory before setup: `cd themes/<your_prefix> && npm install && cd -`.

- Local PHP module warnings (opcache/xdebug/imagick API mismatch)
  - These warnings originate from Local's PHP module configuration and typically do not block the setup process.
  - If desired, resolve by restarting the Local site, or switching/reapplying the PHP version in Local so module API versions match.

This will re-link `.nvmrc` and ensure the correct Node version is active.

> Note: If you’ve already run the script once, it will automatically skip to avoid reapplying changes. To rerun it, delete the `.theme_setup_done` file in the root directory.

#### Project Setup Commands

| Command                                                                 | Action                                                                                          |
|-------------------------------------------------------------------------|-------------------------------------------------------------------------------------------------|
| `git clone git@github.com:WebDevStudios/project-khleomix.git wp-content` | Clones the newly created `project-khleomix` repository into the `wp-content` directory.   |
| `cd wp-content`                                                        | Navigates into the cloned project directory.                                                    |
| `chmod +x setup.sh`                                                   | Makes the setup script executable.                                                              |
| `./setup.sh` or `composer run theme-setup`                                                          | Runs the setup process: prompts for project info, configures the theme, installs dependencies, and sets up Lefthook. |

<p align="right">(<a href="#top">Back to top</a>)</p>

#### Project Theme Commands (WDSBT)

<a id="project-theme-commands"></a>

From the command line, navigate to the project's theme folder, type any of the following to perform an action:

| Command | Action |
| ------- | ------ |
| `npm run a11y` | Triggers Pa11y CI for accessibility checks |
| `npm run build` | Builds production-ready assets for a deployment |
| `npm run create-block` | Scaffold a new block with various configurations |
| `npm run format` | Fix all CSS, JS, MD, and PHP formatting errors automatically |
| `npm run lint` | Check all CSS, JS, MD, and PHP files for errors |
| `npm run setup` | Reset, install dependencies, and build the theme |
| `npm run start` | Builds assets and starts Live Reload server |
| `npm run version-update` | Update the theme version based on environment variable |

For more detailed information about the WDS BT theme, please refer to the [WDS BT README](https://github.com/WebDevStudios/khleomix/blob/main/README.md).

<p align="right">(<a href="#top">Back to top</a>)</p>

### Coding Standards

<a id="coding-standards"></a>

All code is required to be compliant with the WordPress coding standards for PHP & JS. This should be configured to be
automatically linted in your IDE using the wpcs and eslint standards published by WordPress.

For reference, here are the relevant rules:

- [PHP Standards](https://make.wordpress.org/core/handbook/best-practices/coding-standards/php/)
- [JS Standards](https://make.wordpress.org/core/handbook/best-practices/coding-standards/javascript/)

<p align="right">(<a href="#top">Back to top</a>)</p>

### GitHub Workflow Best Practices

<a id="github-workflow-best-practices"></a>

1. __Branch Creation:__
   - Create all branches from `main`
   - Use prefixes:
     - `feature/WDS-XX-description` for new features
     - `bugfix/WDS-XX-description` for bug fixes
     - `hotfix/WDS-XX-description` for urgent fixes

2. __Development Flow:__
   - Test work on `develop` branch
   - Create PR to `main` when ready
   - Assign reviewer for code review
   - Address review feedback
   - Merge to staging for client review
   - Final approval merges to main

3. __Best Practices:__
   - Keep commits focused and documented
   - Never merge `develop` to `staging` or your branch
   - Never merge `staging` to `main` directly
   - Ensure all changes have corresponding tickets
   - Follow PR template guidelines

<p align="right">(<a href="#top">Back to top</a>)</p>

### Release Workflow

<a id="release-workflow"></a>

When you start a new task, create a branch from `main`. This ensures your branch doesn't include any unfinished or unapproved code from `develop` or `staging`. This approach is useful for projects releasing some tasks while others are still in progress. Merging into `main` keeps all branches and environments aligned with the approved work, simplifying the release process.

#### Exception Handling

<a id="exception-handling"></a>

An exception may be granted to create and merge PRs directly to `staging` and `main` after the PR on the `develop` branch has been approved and tested. This allowance is limited to minor changes, such as updating a readme or package file.

#### Begin

To begin a new task:

1. `git checkout main`
2. `git pull`
3. `git checkout -b feature/WDS-XX` Checkout the working branch, branched from main

#### Branch `develop`

To merge with the `develop` server you should follow these steps:

1. `git checkout main`
2. `git pull` make sure `main` branch is up to date
3. `git checkout develop`
4. `git pull` make sure `develop` branch is up to date
5. `git checkout feature/WDS-XX` Checkout the working branch
6. `git merge main` Ensures that your working branch is up to date with main
7. `git checkout develop`
8. `git merge feature/WDS-XX` Make sure that the branch has no conflicts with `develop`
9. `git push`

#### Branch `staging`

This is identical to the previous steps, with the only difference being that your work is relevant to `staging`.
We do not want work on staging branch that has not been approved for this environment.

To merge with the `staging` server you should follow these steps:

1. `git checkout main`
2. `git pull` make sure `main` branch is up to date
3. `git checkout staging`
4. `git pull` make sure `staging` branch is up to date
5. `git checkout feature/WDS-XX` Checkout the working branch
6. `git merge main` Ensures that your working branch is up to date with main
7. `git checkout staging`
8. `git merge feature/WDS-XX` Make sure that the branch has no conflicts with `staging`
9. `git push`

#### Branch `main`

We do not want work on main branch that has not been approved for this environment.

To merge with the `main` server you should follow these steps:

1. `git checkout main`
2. `git pull` make sure `main` branch is up to date
3. `git checkout feature/WDS-XX` Checkout the working branch
4. `git merge main` Ensures that your working branch is up to date with main and has no conflicts
5. `git push` Create a PR for `main` <- `feature/WDS-XX`
6. Create a new Pull Request and assign it to your for review.
7. Once this Pull Request is merged you will need to manually deploy it using [Buddy](hhttps://app.buddy.works/webdevstudios/project-template/pipelines).

#### Changes while PR is open

If there has been changes requested the PR hasn't been closed, do them in the original working branch `WDS-XX-Descriptive-Title` so they don't get lost.

1. `git checkout WDS-XX-Descriptive-Title` Checkout the working branch and do the requested changes
2. `git push` This will auto update the PR

#### Changes after PR closed

If there are changes to be made after the PR has been closed and the `WDS-XX-Descriptive-Title` branch deleted,
follow the above steps to create a new `feature/WDS-XX-Descriptive-Title` branch from `main`.

<p align="right">(<a href="#top">Back to top</a>)</p>

## Project Management

<a id="project-management"></a>

### ClickUp

ClickUp is used to track user stories, time estimates, sprints, project and develop velocity.

#### User Stories

User stories will move from left to right in swimlanes across a
kanban [board](https://app.clickup.com/9011385391/v/b/6-901109676817-2?pr=90112292528).

| Status | Description |
| ----------- | ----------- |
| To Do    | The user story has has been created and is in the sprint but work is yet to commence on the story |
| In Progress | The user story is currently in progress with the current issue assignee |
| Blocked | The user story is currently blocked from progress or awaiting more information |
| WDS Review | The user story has been completed and it is awaiting review from one of the WDS team |
| Client Review | Peer review on the user story has been completed and the Product Owner now needs to review the user story |
| Ready to Deploy | The user story has passed Client Review and it is now ready to deploy. |
| Done | The user story has passed Client Review and it is now ready to close. |

<p align="right">(<a href="#top">Back to top</a>)</p>

## Hosting

<a id="hosting"></a>

All production, staging and development sites are hosted on [HOSTNAME](https://hostname.com/).

There are three branches in GitHub that contain the code powering each of the sites. They are as follows:

- `main` - This branch hosts the production code. Manual triggers are necessary for deploying to production, and only a Lead or Principal Engineer is authorized to perform these deployments. Changes introduced must meet acceptance criteria and undergo thorough testing on the staging branch.
- `staging` - This branch hosts the code for the staging server. Merging triggers automatic deployment to the staging application server. Pushing changes requires a successful PR meeting acceptance criteria. Treat it like production; merged changes should be feature-complete for client review.
- `develop` - This branch hosts the code for the development server. Any merges trigger automatic deployment to the development application server. Considered the testing branch, if changes introduced cause any issues in the environment, promptly address and fix them.

<p align="right">(<a href="#top">Back to top</a>)</p>
