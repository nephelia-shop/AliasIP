-- #! sqlite
-- #{ aliasip

    -- # { init
        CREATE TABLE IF NOT EXISTS players(
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT UNIQUE NOT NULL,
            ips TEXT NOT NULL DEFAULT "[]"
        );
    -- # }

    -- # { auth
    -- # :name string
    -- # :ips string
        INSERT OR IGNORE INTO players(name, ips)
            VALUES (:name, :ips)
    -- # }

    -- # { get
    -- # :name string
        SELECT * FROM players WHERE name = :name;
    -- # }

    -- # { updateIps
    -- # :ips string
    -- # :name string
        UPDATE players SET ips = :ips WHERE name = :name;
    -- # }

    -- # { getAllIps
        SELECT name, ips FROM players;
    -- # }

    -- # { getRelatedAccounts
    -- # :ip string
        SELECT name FROM players WHERE ips= :ips;
    -- # }

    -- # { blacklist

        -- # { init
            CREATE TABLE IF NOT EXISTS blacklist(
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                ip TEXT NOT NULL,
                raison TEXT NOT NULL DEFAULT "raison non spécifiée",
                date_added TEXT
            );
        -- # }

        -- # { add
        -- # :ip string
        -- # :raison string
        -- # :date string
            INSERT INTO blacklist(ip, raison, date_added)
            VALUES (:ip, :raison, :date);
        -- # }

        -- # { remove
        -- # :ip string
            DELETE FROM blacklist WHERE ip = :ip;
        -- # }

        -- # { getAll
            SELECT * FROM blacklist;
        -- # }

    -- # }

-- #}