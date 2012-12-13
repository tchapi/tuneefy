SET @name     := 'gooz';
SET @email    := 'cycogooz@gmail.com';
SET @isActive := 1;

INSERT INTO `tuneefy_prod`.`api_clients` 
  (`id`, `name`, `contact`, `consumer_key`, `consumer_secret`, `active`, `creation_date`) 
VALUES 
  (NULL, @name, @email, MD5(UUID()), SHA1(RAND()), @isActive, NOW());