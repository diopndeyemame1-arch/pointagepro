-- Add column to track if we already sent a 3-absence warning email
ALTER TABLE public.users ADD COLUMN IF NOT EXISTS absence_warning_sent BOOLEAN DEFAULT false;