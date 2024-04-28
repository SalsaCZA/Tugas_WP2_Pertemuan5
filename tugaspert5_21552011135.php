<?php

class Book {
    public $judul;
    public $penulis;
    public $tahun;
    public $pinjam;

    public function __construct($judul, $penulis, $tahun) {
        $this->judul = $judul;
        $this->penulis = $penulis;
        $this->tahun = $tahun;
        $this->pinjam = false;
    }

    public function pinjamBuku() {
        if (!$this->pinjam) {
            $this->pinjam = true;
            return true;
        } else {
            return false; // Buku sedang dipinjam
        }
    }

    public function pengembalianBuku() {
        if ($this->pinjam) {
            $this->pinjam = false;
            return true;
        } else {
            return false; // Buku tidak dipinjam
        }
    }
}

class Library {
    public $books = [];

    public function tambahBuku($book) {
        $this->books[] = $book;
    }

    public function pinjamBuku($judul) {
        foreach ($this->books as $book) {
            if ($book->judul == $judul && !$book->pinjam) {
                return $book->pinjamBuku();
            }
        }
        return false; // Buku tidak ditemukan atau sedang dipinjam
    }

    public function pengembalianBuku($judul) {
        foreach ($this->books as $book) {
            if ($book->judul == $judul && $book->pinjam) {
                return $book->pengembalianBuku();
            }
        }
        return false; // Buku tidak ditemukan atau tidak dipinjam
    }

    public function ketersediaanBuku() {
        $ketersediaanBuku = [];
        foreach ($this->books as $book) {
            if (!$book->pinjam) {
                $ketersediaanBuku[] = $book->judul;
            }
        }
        return $ketersediaanBuku;
    }
}

// Membuat objek buku
$book1 = new Book("Harry Potter", "J.K. Rowling", 1997);
$book2 = new Book("The Great Gatsby", "F. Scott Fitzgerald", 1925);
$book3 = new Book("To Kill a Mockingbird", "Harper Lee", 1960);
$book4 = new Book("1984", "George Orwell", 1949);
$book5 = new Book("Pride and Prejudice", "Jane Austen", 1813);
$book6 = new Book("The Catcher in the Rye", "J.D. Salinger", 1951);
$book7 = new Book("The Lord of the Rings", "J.R.R. Tolkien", 1954);
$book8 = new Book("The Hobbit", "J.R.R. Tolkien", 1937);
$book9 = new Book("The Da Vinci Code", "Dan Brown", 2003);
$book10 = new Book("The Alchemist", "Paulo Coelho", 1988);


// Membuat objek perpustakaan
$library = new Library();

// Menambahkan buku ke perpustakaan
$library->tambahBuku($book1);
$library->tambahBuku($book2);
$library->tambahBuku($book3);
$library->tambahBuku($book4);
$library->tambahBuku($book5);
$library->tambahBuku($book6);
$library->tambahBuku($book7);
$library->tambahBuku($book8);
$library->tambahBuku($book9);
$library->tambahBuku($book10);

// Meminjam buku
$library->pinjamBuku("");

// Mengembalikan buku
$library->pengembalianBuku("");

// Mencetak daftar buku yang tersedia
$ketersediaanBuku = $library->ketersediaanBuku();
echo "Buku yang tersedia di perpustakaan: " . implode(", ", $ketersediaanBuku);
?>