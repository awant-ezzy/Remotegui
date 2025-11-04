# Remotegui
Aplikasi untuk membuat remote mikrotik berbasis web
Struktur Proyek:

src/
├── app/
│   ├── page.tsx (Dashboard utama)
│   ├── api/
│   │   ├── clients/ (Client management)
│   │   ├── dns/ (DNS records)
│   │   ├── activities/ (Activity logs)
│   │   ├── stats/ (Server statistics)
│   │   └── mikrotik/ (Mikrotik integration)
│   └── lib/
│       ├── db.ts (Database connection)
│       └── mikrotik.ts (Mikrotik API service)
├── components/ui/ (shadcn/ui components)
└── prisma/
    └── schema.prisma (Database schema)


    Langkah Instalasinya

    cd /home/z/Remotegui
     git init
