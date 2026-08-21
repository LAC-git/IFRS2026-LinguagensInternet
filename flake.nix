{
  description = "PHP Development Server";

  inputs = {
    nixpkgs.url = "github:nixos/nixpkgs/nixos-unstable";
  };

  outputs = { self, nixpkgs }:
    let
      system = "x86_64-linux";
      pkgs = nixpkgs.legacyPackages.${system};

      myPhp = pkgs.php.buildEnv {
        extensions = { enabled, all }: enabled ++ (with all; [ pdo pdo_mysql pdo_pgsql ]);
      };

    in
    {
      devShells.${system}.default = pkgs.mkShell {
        packages = [ myPhp pkgs.mariadb ];

        shellHook = ''
          WEBROOT="$PWD/www"
          DB_DIR="$PWD/database"

          export DATA_DIR="$DB_DIR/data"
          export SOCKET="$DB_DIR/mysql.sock"
          export PID_FILE="$DB_DIR/mysql.pid"
          export ERROR_LOG="$DB_DIR/mysql-error.log"

          # Creates database/ and database/data/ in one shot
          mkdir -p "$DATA_DIR"

          # Cleanup function — disarms all traps first to prevent double-firing,
          # then only shuts down if something is actually running.
          cleanup() {
            trap - INT TERM EXIT
            if [ -f "$PID_FILE" ] && kill -0 "$(cat "$PID_FILE")" 2>/dev/null; then
              echo ""
              echo "Shutting down services..."
              kill "$(cat "$PID_FILE")" 2>/dev/null
              rm -f "$PID_FILE"
              pkill -f "mariadbd.*$SOCKET" 2>/dev/null || true
              echo "Services stopped."
            fi
          }

          trap cleanup INT TERM EXIT

          # Initialize data directory if this is the first run
          if [ ! -d "$DATA_DIR/mysql" ]; then
            echo "Initializing database..."
            ${pkgs.mariadb}/bin/mariadb-install-db \
              --datadir="$DATA_DIR" \
              --auth-root-authentication-method=normal > /dev/null 2>&1
          fi

          # Stop any leftover instance from a previous session
          if [ -f "$PID_FILE" ] && kill -0 "$(cat "$PID_FILE")" 2>/dev/null; then
            echo "Stopping existing database..."
            kill "$(cat "$PID_FILE")" 2>/dev/null
            sleep 2
          fi

          # Start MariaDB in the background
          echo "Starting database..."
          ${pkgs.mariadb}/bin/mariadbd \
            --datadir="$DATA_DIR" \
            --socket="$SOCKET" \
            --skip-networking \
            --silent-startup \
            --pid-file="$PID_FILE" \
            --log-error="$ERROR_LOG" \
            &

          # Wait until the socket is ready
          while [ ! -e "$SOCKET" ]; do sleep 1; done
          echo "Database ready."

          # Importa o schema.sql diretamente (ele cria o banco)
          if [ -f "$DB_DIR/schema.sql" ]; then
              ${pkgs.mariadb}/bin/mariadb --socket="$SOCKET" < "$DB_DIR/schema.sql" 2>/dev/null || true
          fi

          # Drop a default index.php if the project folder is empty
          [ ! -f "$WEBROOT/index.php" ] && echo "<?php phpinfo();" > "$WEBROOT/index.php"

          echo "------------------------------------------------------------------------------------"
          echo "PHP Server:   http://localhost:8080"
          echo "PHP Version:  $(php -v | head -n 1)"
          echo "Project root: $WEBROOT"
          echo "DB socket:    $SOCKET"
          echo "-------------------------------------------------------------------------------------"

          # Run PHP server in the foreground.
          # Ctrl+C kills PHP and fires the INT trap → cleanup runs once.
          php -S localhost:8080 -t "$WEBROOT"

          exit 0
        '';
      };
    };
}
