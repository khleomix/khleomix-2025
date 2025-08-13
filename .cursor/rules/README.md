# Cursor Rules Documentation

## Project Overview
- **Project Name**: WDS-BT Block Theme Starter
- **Type**: WordPress Block Theme
- **Technologies**: PHP, JavaScript, JSX, JSON, SCSS

## Branch Management Rules

### Branch Protection and Merge Rules

#### Main Branch
- Protected branch
- Only accepts merges through pull requests
- Requires code review and approval
- No direct pushes allowed

#### Staging Branch
- Can accept merges from:
  - Main branch
  - Feature branches
- Requires an open pull request
- Can merge through command line or GitHub interface
- Pull request must exist but merge can happen outside PR

#### Development Branch
- Can accept merges from:
  - Main branch
  - Feature branches
- More flexible for development workflow
- Direct merges allowed
- No PR requirement

### Branch Naming Convention
```
feature/WDS-XX-description
```
Where:
- `feature/` is the branch type prefix
- `WDS-XX` is the ticket/issue number
- `description` is a brief, hyphenated description of the change

## Git Hooks
- Managed through Lefthook
- Version: 1.11.13

## Node.js Requirements
- Node.js version: ≥ 22
- npm version: ≥ 10

## Development Dependencies
- cross-env: ^7.0.3
- lefthook: ^1.11.13

## Best Practices
1. Always create a pull request for changes going into staging or main
2. Use descriptive branch names following the convention
3. Keep commits focused and well-documented
4. Follow the established merge flow:
   - Feature branches → Staging (with PR)
   - Main → Staging (with PR)
   - PRs only → Main
