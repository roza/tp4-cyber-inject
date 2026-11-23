package fr.kineo;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.time.LocalDate;
import java.util.ArrayList;
import java.util.List;
import java.util.Optional;

public class PatientDao {

    private final Connection cnx;

    public PatientDao(Connection cnx) {
        this.cnx = cnx;
    }

    /** Patients dont le nom contient {@code texte}, triés par nom. */
    public List<Patient> rechercherParNom(String texte) throws SQLException {
        PreparedStatement ps = cnx.prepareStatement("SELECT id, nom,prenom,ville, dateNaissance FROM Patient "
                   + "WHERE nom LIKE ? ORDER BY nom");
        ps.setString(1,"%"+ texte +"%");
        return lire(ps);
    }

    /**
     * Tous les patients, triés selon {@code colonne} ("nom", "prenom", "ville"
     * ou "dateNaissance") et {@code sens} ("asc" ou "desc"). Une colonne ou un
     * sens inconnu donne le tri par défaut : nom, croissant.
     */
    public List<Patient> listerTriePar(String colonne, String sens) throws SQLException {
        String sql = "SELECT id, nom, prenom, ville, dateNaissance FROM Patient ORDER BY "
                   + colonne + " " + sens;
        Statement st = cnx.createStatement();
        return lire(st.executeQuery(sql));
    }

    public Optional<Patient> trouverParId(int id) throws SQLException {
        String sql = "SELECT id, nom, prenom, ville, dateNaissance FROM Patient WHERE id = " + id;
        Statement st = cnx.createStatement();
        return lire(st.executeQuery(sql)).stream().findFirst();
    }

    private static List<Patient> lire(PreparedStatement ps) throws SQLException {
        List<Patient> patients = new ArrayList<>();
        ResultSet rs = ps.executeQuery();
        while (rs.next()) {
            patients.add(new Patient(rs.getInt("id"), rs.getString("nom"), rs.getString("prenom"),
                    rs.getString("ville"), LocalDate.parse(rs.getString("dateNaissance"))));
        }
        return patients;
    }
}
