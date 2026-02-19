# Small Project Group 7

## AI Assistance Disclosure

This project was developed with assistance from generative AI tools:

- **Tool**: Claude 4.5 Sonnet (GitHub Copilot)
- **Dates**: February 11, 2026
- **Scope**: CSS styling for sizing to keep web app usable on both
  mobile and desktop devices
- **Use**: Gave Claude the CSS types we were using and had it
  implement the same types across all html files, instead of 
  manually going in and changing them.

- **Tool**: Claude 4.5 Sonnet (GitHub Copilot)
- **Dates**: February 1, 2026
- **Scope**: GitHub deploy workflow and README documentation
- **Use**: Generated the deploy.yml file to deploy our site to
  our server. Also formatted and structured the README.md file based on
  provided content outline and workflow requirements.

- **Tool**: Chat GPT 5.2
- **Dates**: February 8, 2026
- **Scope**: Web app logo image
- **Use**: Generate logo image of dog for the web app header

- **Tool**: Claude 4.5 Sonnet (Anthropic, claude.ai)
- **Dates**: January 20 - 21, 2026; February 11 - 14
- **Scope**: Specific design logic for the API. Helped make the specific SQL logic code for login, signup, deletecontact, and addcontact. Also implemented it for partial search logic in the 4 cases in searchcontact.php.
- **Use**: Generated code for specific logic parts across the API files. Also helped me understand the logic behind PHP code and how it is implemented. Claude aided me in my debugging of why incorrect error codes were being returned and helped me find parts across login, signup, addcontact, and deletecontact where I did not fully complete the logic, causing incorrect 200 codes instead of 400, 409, 500, etc.

**Tool**: ChatGPT-5.2
**Dates**: January 19-21; February 2-4
**Scope**: Improve understanding of MySQL databases and commands
**Use**: Generated 100 test entries in contacts table, verified safety of executing commands on a deployed database to edit tables, and asked for clarification on how primary and foreign keys work.

All AI-generated code was reviewed, tested, and modified to meet 
assignment requirements. Final implementation reflects my understanding 
of the concepts.

## Git Workflow

Follow this workflow when contributing to the project:

### 1. Get the Most Recent Changes

Before starting any work, pull the latest changes from the `dev` branch:

```bash
git checkout dev
git pull origin dev
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

## Testing Features in Staging

Our `staging` branch is used for live deployment testing. Here's how to test your feature branches before merging to `dev`:

### Testing Your Feature Branch in Staging

1. **Push your feature branch to the staging branch:**
   ```bash
   git checkout staging
   git merge <your-feature-branch>
   git push origin staging
   ```

2. **Test your changes on the live server:**
   - Visit `http://209.97.158.98` to see your changes deployed
   - The deployment happens automatically via GitHub Actions

3. **Once testing is complete, reset staging:**
   ```bash
   git checkout staging
   git reset --hard origin/dev
   git push origin staging --force
   ```

### ⚠️ CRITICAL STAGING RULES

**NEVER do the following with the staging branch:**
- ❌ NEVER merge `staging` into `dev`
- ❌ NEVER merge `staging` into `main`
- ❌ NEVER open a Pull Request FROM `staging`
- ❌ NEVER keep experimental code in `staging` permanently

**Why?** The `staging` branch is a temporary testing environment. It should mirror `dev` when not actively testing features.

### Staging Workflow Best Practices

1. `staging` should normally mirror `dev`
2. Only merge feature branches into `staging` temporarily for testing
3. Always reset `staging` back to `dev` after testing
4. Only merge tested features from your feature branch into `dev`, not from `staging`

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
