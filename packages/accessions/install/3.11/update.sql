-- Update DBVersion for Accessions
UPDATE tblCore_Packages SET DBVersion = '3.11' WHERE APRCode = 'accessions';
