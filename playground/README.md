# Playground

Two modes, switched by `KIRBY_DEBUG` in `.env`:

- **Shared demo** (`false`): no frontend, every URL goes to the Panel, `/panel/login` signs in the `playground` role, changes are never written.
- **Local testing** (`true`): pages render under `/`, `/panel/login` signs in as admin, `/panel/login?role=playground` as the role that may preview but not update.

`composer dev` serves it on `localhost:8000`. The admin account is `admin@example.com` with the same password.
