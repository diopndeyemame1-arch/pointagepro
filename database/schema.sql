-- ============================================================
--  PointagePro – Schéma PostgreSQL complet
--  À exécuter une seule fois sur la base de données Render
-- ============================================================

-- 1. Départements
CREATE TABLE IF NOT EXISTS departments (
    id          SERIAL PRIMARY KEY,
    name        VARCHAR(150) NOT NULL,
    description TEXT,
    created_at  TIMESTAMP DEFAULT NOW()
);

-- 2. Cohortes (rattachées à un département)
CREATE TABLE IF NOT EXISTS cohorts (
    id            SERIAL PRIMARY KEY,
    department_id INTEGER REFERENCES departments(id) ON DELETE SET NULL,
    name          VARCHAR(150) NOT NULL,
    status        VARCHAR(50)  DEFAULT 'active',
    created_at    TIMESTAMP    DEFAULT NOW()
);

-- 3. Horaires de cohorte
CREATE TABLE IF NOT EXISTS cohort_schedules (
    id         SERIAL PRIMARY KEY,
    cohort_id  INTEGER   NOT NULL REFERENCES cohorts(id) ON DELETE CASCADE,
    day        VARCHAR(20) NOT NULL,
    start_time TIME        NOT NULL,
    end_time   TIME        NOT NULL
);

-- 4. Utilisateurs
CREATE TABLE IF NOT EXISTS users (
    id              SERIAL PRIMARY KEY,
    firstname       VARCHAR(100) NOT NULL,
    lastname        VARCHAR(100) NOT NULL,
    email           VARCHAR(200) NOT NULL UNIQUE,
    password_hash   TEXT,
    role            VARCHAR(50)  NOT NULL DEFAULT 'etudiant',
    department      VARCHAR(150),
    department_id   INTEGER REFERENCES departments(id) ON DELETE SET NULL,
    cohort          VARCHAR(150),
    cohort_id       INTEGER REFERENCES cohorts(id) ON DELETE SET NULL,
    position        VARCHAR(150),
    phone           VARCHAR(30),
    photo           VARCHAR(255),
    is_active       BOOLEAN   DEFAULT FALSE,
    is_verified     BOOLEAN   DEFAULT FALSE,
    activation_token VARCHAR(255),
    reset_token      VARCHAR(255),
    reset_token_expires_at TIMESTAMP,
    created_at      TIMESTAMP DEFAULT NOW(),
    updated_at      TIMESTAMP DEFAULT NOW()
);

-- 5. Pointages (présences)
CREATE TABLE IF NOT EXISTS attendances (
    id         SERIAL PRIMARY KEY,
    user_id    INTEGER   NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    date       DATE      NOT NULL DEFAULT CURRENT_DATE,
    check_in   TIME,
    check_out  TIME,
    status     VARCHAR(50) DEFAULT 'present',
    created_at TIMESTAMP DEFAULT NOW(),
    UNIQUE (user_id, date)
);

-- 6. Absences
CREATE TABLE IF NOT EXISTS absences (
    id          SERIAL PRIMARY KEY,
    user_id     INTEGER   NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    type        VARCHAR(100),
    start_date  DATE,
    end_date    DATE,
    duration    INTEGER,
    reason      TEXT,
    status      VARCHAR(50) DEFAULT 'en_attente',
    created_at  TIMESTAMP DEFAULT NOW(),
    updated_at  TIMESTAMP DEFAULT NOW()
);

-- 7. Horaires administrateur
CREATE TABLE IF NOT EXISTS admin_schedules (
    id         SERIAL PRIMARY KEY,
    mon_start  TIME, mon_end TIME,
    tue_start  TIME, tue_end TIME,
    wed_start  TIME, wed_end TIME,
    thu_start  TIME, thu_end TIME,
    fri_start  TIME, fri_end TIME
);
INSERT INTO admin_schedules (mon_start, mon_end, tue_start, tue_end, wed_start, wed_end, thu_start, thu_end, fri_start, fri_end)
VALUES ('08:00', '17:00', '08:00', '17:00', '08:00', '17:00', '08:00', '17:00', '08:00', '17:00')
ON CONFLICT DO NOTHING;

-- 8. Paramètres GPS
CREATE TABLE IF NOT EXISTS settings (
    id          INTEGER PRIMARY KEY DEFAULT 1,
    school_lat  DOUBLE PRECISION,
    school_lng  DOUBLE PRECISION,
    radius      INTEGER DEFAULT 100,
    gps_enabled BOOLEAN DEFAULT FALSE
);
INSERT INTO settings (id) VALUES (1) ON CONFLICT DO NOTHING;

-- 9. Paramètres entreprise
CREATE TABLE IF NOT EXISTS company_settings (
    id            INTEGER PRIMARY KEY DEFAULT 1,
    company_name  VARCHAR(200) DEFAULT 'PointagePro',
    company_email VARCHAR(200)
);
INSERT INTO company_settings (id) VALUES (1) ON CONFLICT DO NOTHING;

-- 10. Jours fériés
CREATE TABLE IF NOT EXISTS public_holidays (
    id           SERIAL PRIMARY KEY,
    holiday_name VARCHAR(200) NOT NULL,
    holiday_date DATE         NOT NULL UNIQUE,
    holiday_type VARCHAR(100),
    description  TEXT,
    status       VARCHAR(50)  DEFAULT 'avenir',
    created_at   TIMESTAMP    DEFAULT NOW()
);

-- 11. Logs d'audit
CREATE TABLE IF NOT EXISTS audit_logs (
    id         SERIAL PRIMARY KEY,
    user_id    INTEGER REFERENCES users(id) ON DELETE SET NULL,
    action     VARCHAR(100) NOT NULL,
    entity     VARCHAR(100) NOT NULL,
    entity_id  VARCHAR(255),
    ip         VARCHAR(45),
    created_at TIMESTAMP DEFAULT NOW()
);

-- ============================================================
--  Compte administrateur par défaut
--  Email    : admin@pointagepro.com
--  Password : Admin1234
-- ============================================================
INSERT INTO users (firstname, lastname, email, password_hash, role, is_active, is_verified)
VALUES (
    'Admin',
    'PointagePro',
    'admin@pointagepro.com',
    '$2y$12$7z.mA5WsKBFPXNdec7vF4OsLaXCiWlpwUjmjxdS7Rq9kEuwFiH.Cm', -- 'Admin1234'
    'admin',
    TRUE,
    TRUE
) ON CONFLICT (email) DO NOTHING;
