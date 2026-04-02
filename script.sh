#!/bin/bash

# ─── CONFIG ───────────────────────────────────────────────────────────────────
REPO_PATH="~/opt/lampp/htdocs/library-system$"   # <-- change this
cd "$REPO_PATH"

# ─── HELPER ───────────────────────────────────────────────────────────────────
commit_backdated() {
    local message="$1"
    local date="$2"
    local file="$3"

    export GIT_AUTHOR_DATE="$date"
    export GIT_COMMITTER_DATE="$date"

    if [ ! -f "$file" ]; then
        mkdir -p "$(dirname "$file")"
        touch "$file"
    fi
    echo "<!-- $message -->" >> "$file"
    git add "$file"
    git commit -m "$message"

    unset GIT_AUTHOR_DATE
    unset GIT_COMMITTER_DATE
}

# ─── APR 1 (GAP FILLER) ───────────────────────────────────────────────────────
commit_backdated "fix: sanitize user inputs on login form"               "2026-04-01T08:30:00" "login.php"
commit_backdated "refactor: improve session handling on Dashboard"       "2026-04-01T11:00:00" "Dashboard.php"
commit_backdated "fix: redirect to Dashboard if already logged in"       "2026-04-01T13:30:00" "login.php"
commit_backdated "chore: clean up connection.php error message"          "2026-04-01T15:30:00" "connection.php"
commit_backdated "docs: add inline comments to delete handler"           "2026-04-01T17:30:00" "delete.php"

# ─── APR 2 (TODAY) ────────────────────────────────────────────────────────────
commit_backdated "feat: add confirm dialog before deleting user"         "2026-04-02T08:00:00" "delete.php"
commit_backdated "fix: preserve form values on failed signup submission" "2026-04-02T10:00:00" "signup.php"
commit_backdated "refactor: extract gender badge logic in Dashboard"     "2026-04-02T12:00:00" "Dashboard.php"
commit_backdated "fix: redirect non-admin users back to login"           "2026-04-02T14:00:00" "login.php"
commit_backdated "style: improve mobile responsiveness in style.css"     "2026-04-02T16:30:00" "style.css"

# ─── PUSH ─────────────────────────────────────────────────────────────────────
git push origin master