{ pkgs, ... }:

let
  phpWithExtensions = pkgs.php.buildEnv {
    extensions = { all, ... }: with all; [
      pdo
      pdo_mysql
      mbstring
      tokenizer
      curl
      openssl
      dom
      fileinfo
    ];
    extraConfig = ''
      memory_limit = -1
    '';
  };

  # Composer wrapper script (downloaded locally if missing)
  composerBin = pkgs.writeShellScriptBin "composer" ''
    if [ ! -f .composer/composer.phar ]; then
      mkdir -p .composer
      curl -sS https://getcomposer.org/installer | ${phpWithExtensions}/bin/php -- --install-dir=.composer --filename=composer.phar
    fi
    exec ${phpWithExtensions}/bin/php .composer/composer.phar "$@"
  '';
in
{
  cachix.enable = false;

  languages.php.enable = true;
  languages.php.version = "8.2";

  languages.javascript.enable = true;
  languages.javascript.package = pkgs.nodejs_20;

  services.mysql.enable = true;
  services.mysql.package = pkgs.mariadb;
  services.mysql.ensureUsers = [
    {
      name = "devenv";
      ensurePermissions = {
        "devenv.*" = "ALL PRIVILEGES";
      };
    }
  ];
  services.mysql.initialDatabases = [
    { name = "devenv"; }
  ];

  packages = with pkgs; [
    git
    phpWithExtensions
    composerBin
    nodejs_20
    mariadb
  ];

  enterShell = ''
    echo "Laravel development environment ready!"
    echo "Running composer install (if needed)..."
    composer install || true

    echo "Running npm install (if needed)..."
    npm install || true
  '';

  processes = {
    server.exec = "php -S localhost:9876 -t public/";
    vite.exec = "npm run dev";
  };
}
