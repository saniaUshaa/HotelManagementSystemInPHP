
<link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
      @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
      ::after,
      ::before {
          box-sizing: border-box;
          margin: 0;
          padding: 0;
      }

      a {
          text-decoration: none;
      }

      li {
          list-style: none;
      }

      h1 {
          font-weight: 600;
          font-size: 1.5rem;
      }

      body {
          font-family: 'Poppins', sans-serif;
      }

      .wrapper {
          display: flex;
      }

      .main {
          min-height: 100vh;
          width: 100%;
          overflow: hidden;
          transition: all 0.35s ease-in-out;
          background-color: #fafbfe;
      }

      #sidebar {
          width: 70px;
          min-width: 70px;
          z-index: 1000;
          transition: all .25s ease-in-out;
          background-color: #0e2238;
          display: flex;
          flex-direction: column;
      }

      #sidebar.expand {
          width: 260px;
          min-width: 260px;
      }

      .toggle-btn {
          background-color: transparent;
          cursor: pointer;
          border: 0;
          padding: 1rem 1.5rem;
      }

      .toggle-btn i {
          font-size: 1.5rem;
          color: #FFF;
      }

      .sidebar-logo {
          margin: auto 0;
      }

      .sidebar-logo a {
          color: #FFF;
          font-size: 1.15rem;
          font-weight: 600;
      }

      #sidebar:not(.expand) .sidebar-logo,
      #sidebar:not(.expand) a.sidebar-link span {
          display: none;
      }

      .sidebar-nav {
          padding: 2rem 0;
          flex: 1 1 auto;
      }

      a.sidebar-link {
          padding: .625rem 1.625rem;
          color: #FFF;
          display: block;
          font-size: 0.9rem;
          white-space: nowrap;
          border-left: 3px solid transparent;
      }

      .sidebar-link i {
          font-size: 1.1rem;
          margin-right: .75rem;
      }

      a.sidebar-link:hover {
          background-color: rgba(255, 255, 255, .075);
          border-left: 3px solid #3b7ddd;
      }

      .sidebar-item {
          position: relative;
      }

      #sidebar:not(.expand) .sidebar-item .sidebar-dropdown {
          position: absolute;
          top: 0;
          left: 70px;
          background-color: #0e2238;
          padding: 0;
          min-width: 15rem;
          display: none;
      }

      #sidebar:not(.expand) .sidebar-item:hover .has-dropdown+.sidebar-dropdown {
          display: block;
          max-height: 15em;
          width: 100%;
          opacity: 1;
      }

      #sidebar.expand .sidebar-link[data-bs-toggle="collapse"]::after {
          border: solid;
          border-width: 0 .075rem .075rem 0;
          content: "";
          display: inline-block;
          padding: 2px;
          position: absolute;
          right: 1.5rem;
          top: 1.4rem;
          transform: rotate(-135deg);
          transition: all .2s ease-out;
      }

      #sidebar.expand .sidebar-link[data-bs-toggle="collapse"].collapsed::after {
          transform: rotate(45deg);
          transition: all .2s ease-out;
      }
    </style>