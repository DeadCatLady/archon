-- Copy Digital Library files into Files table from FileContents table -- NOTICE -- THIS MAY TAKE A LONG TIME
UPDATE tblDigitalLibrary_Files SET tblDigitalLibrary_Files.FileContents = CONVERT(VARBINARY(max), tblDigitalLibrary_FileContents.FileContents) FROM tblDigitalLibrary_Files, tblDigitalLibrary_FileContents WHERE tblDigitalLibrary_FileContents.ID = tblDigitalLibrary_Files.FileContentsID;
UPDATE tblDigitalLibrary_Files SET tblDigitalLibrary_Files.FilePreviewLong = CONVERT(VARBINARY(max), tblDigitalLibrary_FileContents.FileContents) FROM tblDigitalLibrary_Files, tblDigitalLibrary_FileContents WHERE tblDigitalLibrary_FileContents.ID = tblDigitalLibrary_Files.FilePreviewLongID;
UPDATE tblDigitalLibrary_Files SET tblDigitalLibrary_Files.FilePreviewShort = CONVERT(VARBINARY(max), tblDigitalLibrary_FileContents.FileContents) FROM tblDigitalLibrary_Files, tblDigitalLibrary_FileContents WHERE tblDigitalLibrary_FileContents.ID = tblDigitalLibrary_Files.FilePreviewShortID;
