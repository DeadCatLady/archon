-- Insert Configuration Directives for Content Level Subjects and Creators
DECLARE @package_collections INT; SET @package_collections = (SELECT ID FROM tblCore_Packages WHERE APRCode = 'collections'); INSERT INTO tblCore_Configuration (PackageID, ModuleID, Directive, Value, InputType, PatternID, ReadOnly, Encrypted, ListDataSource) VALUES (@package_collections, '0', 'Enable Content Level Creators', '1', 'radio', '3', '0', '0', NULL);
DECLARE @package_collections INT; SET @package_collections = (SELECT ID FROM tblCore_Packages WHERE APRCode = 'collections'); INSERT INTO tblCore_Configuration (PackageID, ModuleID, Directive, Value, InputType, PatternID, ReadOnly, Encrypted, ListDataSource) VALUES (@package_collections, '0', 'Enable Content Level Subjects', '1', 'radio', '3', '0', '0', NULL);

-- Insert Configuration Directives for External System Events
DECLARE @package_collections INT; SET @package_collections = (SELECT ID FROM tblCore_Packages WHERE APRCode = 'collections'); INSERT INTO tblCore_Configuration (PackageID, ModuleID, Directive, Value, InputType, PatternID, ReadOnly, Encrypted, ListDataSource) VALUES (@package_collections, '0', 'Invoke External System', '0', 'radio', '3', '0', '0', NULL);
DECLARE @package_collections INT; SET @package_collections = (SELECT ID FROM tblCore_Packages WHERE APRCode = 'collections'); INSERT INTO tblCore_Configuration (PackageID, ModuleID, Directive, Value, InputType, PatternID, ReadOnly, Encrypted, ListDataSource) VALUES (@package_collections, '0', 'External URL For EAD Export', '', 'textfield', '1', '0', '0', NULL);
DECLARE @package_collections INT; SET @package_collections = (SELECT ID FROM tblCore_Packages WHERE APRCode = 'collections'); INSERT INTO tblCore_Configuration (PackageID, ModuleID, Directive, Value, InputType, PatternID, ReadOnly, Encrypted, ListDataSource) VALUES (@package_collections, '0', 'External Target For EAD Export', '', 'textfield', '1', '0', '0', NULL);
DECLARE @package_collections INT; SET @package_collections = (SELECT ID FROM tblCore_Packages WHERE APRCode = 'collections'); INSERT INTO tblCore_Configuration (PackageID, ModuleID, Directive, Value, InputType, PatternID, ReadOnly, Encrypted, ListDataSource) VALUES (@package_collections, '0', 'External URL For Collection Deletion', '', 'textfield', '1', '0', '0', NULL);
DECLARE @package_collections INT; SET @package_collections = (SELECT ID FROM tblCore_Packages WHERE APRCode = 'collections'); INSERT INTO tblCore_Configuration (PackageID, ModuleID, Directive, Value, InputType, PatternID, ReadOnly, Encrypted, ListDataSource) VALUES (@package_collections, '0', 'External Target For Collection Deletion', '', 'textfield', '1', '0', '0', NULL);

-- Insert Configuration Directives for Public EAD List
DECLARE @package_collections INT; SET @package_collections = (SELECT ID FROM tblCore_Packages WHERE APRCode = 'collections'); INSERT INTO tblCore_Configuration (PackageID, ModuleID, Directive, Value, InputType, PatternID, ReadOnly, Encrypted, ListDataSource) VALUES (@package_collections, '0', 'Enable Public EAD List', '0', 'radio', '3', '0', '0', NULL);
