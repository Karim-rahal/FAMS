export function PlayerList() {
    const players = [
      { name: 'John Doe', position: 'Forward' },
      { name: 'Ali Hassan', position: 'Midfield' },
      { name: 'Mark Smith', position: 'Goalkeeper' },
    ];
    return (
      <section className="bg-white rounded p-4 shadow">
        <h2 className="text-xl font-semibold mb-2">Team Roster</h2>
        <table className="w-full">
          <thead><tr><th>Name</th><th>Position</th></tr></thead>
          <tbody>
            {players.map((p, i) => (
              <tr key={i}><td>{p.name}</td><td>{p.position}</td></tr>
            ))}
          </tbody>
        </table>
      </section>
    );
  }
  