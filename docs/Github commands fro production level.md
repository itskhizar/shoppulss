Github commands fro production level 
Stage 1 — Save the new code to GitHub

On your Windows local machine, open PowerShell in the ShopPulss project:

cd C:\shoppulss

First check exactly what changed:

git status

Then check whether there are database migrations:

git status --short database/migrations

And check the migration files themselves:

git diff -- database/migrations
Important

Do not run any database import or migrate:fresh.

If Antigravity created migration files, we will deploy those migrations to the VPS with:

php artisan migrate --force


Stage 2 — Save these changes to GitHub

You are already in:

C:\shoppulss

Run:

git add .

Then:

git status

Stage 3 — Create the Git commit

Run this on your Windows machine:

git commit -m "Add manual payments and update ShopPulss UI"

Stage 4 — Push the code to GitHub

Now run only:

git push origin main

Stage 5 — Pull the new code on the VPS

SSH into your VPS as you normally do, then run:

cd /var/www/shoppulss
git status

You should ideally see:

On branch main
Your branch is behind 'origin/main' by 1 commit
nothing to commit, working tree clean

Then run:

git pull origin main

You should see the new commit:





Antigravuty cmmnds 
cd /var/www/shoppulss

# 1. Pull the latest route aliases from main
git pull origin main

# 2. Clear the compiled route, config, and view cache
php artisan optimize:clear

# 3. Run the database migrations (creates pages and redirects tables)
php artisan migrate --force

# 4. Seed the 10 legal policy pages and 301 alias mappings
php artisan db:seed --class=PageSeeder --force

# 5. Re-optimize the application for production
php artisan optimize
