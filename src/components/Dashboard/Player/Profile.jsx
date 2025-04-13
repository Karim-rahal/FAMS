export function PlayerProfile() {
    return (
      <section className="bg-white rounded p-4 shadow">
        <h2 className="text-xl font-semibold mb-2">Player Profile</h2>
        <div className="grid grid-cols-2 gap-4">
          <p><strong>Name:</strong> John Doe</p>
          <p><strong>Email:</strong> john@example.com</p>
          <p><strong>Position:</strong> Forward</p>
          <p><strong>Age:</strong> 17</p>
          <p><strong>Joined:</strong> 2023-02-01</p>
          <p><strong>Height:</strong> 1.78m</p>
        </div>
      </section>
    );
  }