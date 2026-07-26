-- Supprime la colonne absence_warning_sent de la table users
-- On utilise désormais la table audit_logs pour tracker les envois
-- Voir app/Helpers/AbsenceWarningHelper.php
ALTER TABLE public.users DROP COLUMN IF EXISTS absence_warning_sent;