# Small Project Group 7

## Git Workflow

Follow this workflow when contributing to the project:

### 1. Get the Most Recent Changes

Before starting any work, pull the latest changes from the `dev` branch:

```bash
git pull dev
```

### 2. Create a Feature Branch

Create a new branch for your work. Use the naming convention based on what you're working on:

**Frontend developers:**
```bash
git checkout -b front-end/<feature-name>
```

**API developers:**
```bash
git checkout -b api/<feature-name>
```

**Database developers:**
```bash
git checkout -b database/<feature-name>
```

Replace `<feature-name>` with a descriptive name for the feature you're implementing (e.g., `front-end/user-authentication`, `api/database-optimization`).

### 3. Commit Changes Regularly

As you work, commit your changes regularly with clear, descriptive commit messages:

```bash
git add .
git commit -m "Description of changes made"
```

### 4. Push Your Changes

Push your branch when you reach a good point in your work. A "good point" means:
- Your code compiles/runs without errors
- You've completed a logical unit of work (a complete feature or significant portion)
- Your changes are tested and working as intended
- You want to back up your work or share progress with the team

```bash
git push origin <your-branch-name>
```

or 
```bash
git push
```

### 5. Create a Pull Request

The first time you push, you'll see a pull request link in the terminal. **CMD+click on this link** to open the pull request in your browser.

**⚠️ Important:** Make sure you set the base branch to `dev`, **NOT** `main`. This ensures your changes are reviewed and tested on the development branch before being merged to production.

### 6. Merge Into Dev

Once your feature is complete and reviewed, merge it into the `dev` branch:

```bash
git checkout dev
git merge <your-branch-name>
```

Alternatively, you can merge through the pull request interface on GitHub.

## Using VS Code Git Control

VS Code has built-in git tools that make version control easier without using the terminal:

### Accessing Git Control

1. Click the **Source Control** icon in the left sidebar (it looks like a branch)
2. Or use the keyboard shortcut: `Ctrl+Shift+G` (Windows/Linux) or `Cmd+Shift+G` (Mac)

### Basic Operations

**Staging Changes:**
- View all modified files in the "Changes" section
- Click the `+` icon next to a file to stage it for commit
- Or click the `+` icon in the "Changes" header to stage all files

**Committing Changes:**
- Enter a commit message in the text field at the top of the Source Control panel
- Click the checkmark icon to commit, or press `Ctrl+Enter` (Windows/Linux) or `Cmd+Enter` (Mac)

**Switching Branches:**
- Click the branch name at the bottom left of the VS Code window
- Select an existing branch or create a new one from the dropdown menu

**Pushing/Pulling:**
- Use the `...` menu in the Source Control panel
- Select `Push` or `Pull` to sync your changes
- Or use the sync icon (circular arrows) at the bottom of the window for a quick pull/push

**Viewing History:**
- Right-click a file in the Source Control panel and select "Open Timeline" to see commit history
- Or use the `Gitlens` extension for more advanced history viewing

### Creating a Branch in VS Code

1. Click the branch name at the bottom left
2. Select "Create New Branch..."
3. Enter your branch name following the convention: `front-end/<feature>` or `api/<feature>`
4. Select `dev` as the base branch when prompted

---

